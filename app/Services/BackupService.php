<?php

namespace App\Services;

use App\Models\BackupConfig;
use App\Models\BackupLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Exception;

class BackupService
{
    protected BackupConfig $backupConfig;
    protected BackupLog $backupLog;
    protected string $tempConnectionName;
    protected array $tablesToBackup = [];
    protected int $recordsBackedUp = 0;

    /**
     * Ejecutar backup completo
     */
    public function executeBackup(BackupConfig $backupConfig, $userId = null): BackupLog
    {
        $this->backupConfig = $backupConfig;
        $this->tempConnectionName = 'backup_' . time();

        // Validar que no sea la BD de producción
        if ($backupConfig->isProductionDatabase()) {
            throw new Exception('No se puede hacer backup a la misma base de datos de producción');
        }

        // Crear log de backup
        $this->backupLog = BackupLog::create([
            'backup_config_id' => $backupConfig->id,
            'user_id' => $userId,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        try {
            // Paso 1: Crear/verificar base de datos destino
            $this->createOrVerifyDatabase();

            // Paso 2: Configurar conexión temporal
            $this->setupTempConnection();

            // Paso 3: Ejecutar migraciones
            $this->runMigrations();

            // Paso 4: Obtener lista de tablas
            $this->getTablesList();

            // Paso 5: Copiar datos de cada tabla
            $this->copyTablesData();

            // Paso 6: Ejecutar seeders si está configurado
            if ($backupConfig->execute_seeders) {
                $this->runSeeders();
            }

            // Paso 7: Marcar como completado
            $this->backupLog->update([
                'status' => 'completed',
                'completed_at' => now(),
                'tables_backed_up' => count($this->tablesToBackup),
                'tables_total' => count($this->tablesToBackup),
                'records_backed_up' => $this->recordsBackedUp,
                'details' => [
                    'tables' => $this->tablesToBackup,
                    'total_records' => $this->recordsBackedUp,
                ],
            ]);

            // Actualizar configuración
            $backupConfig->update([
                'last_backup_at' => now(),
                'last_backup_result' => [
                    'status' => 'success',
                    'tables' => count($this->tablesToBackup),
                    'records' => $this->recordsBackedUp,
                    'completed_at' => now()->toDateTimeString(),
                ],
            ]);

            // Limpiar conexión temporal
            DB::purge($this->tempConnectionName);

            Log::info('Backup completado exitosamente', [
                'backup_config_id' => $backupConfig->id,
                'tables' => count($this->tablesToBackup),
                'records' => $this->recordsBackedUp,
            ]);

            return $this->backupLog;

        } catch (Exception $e) {
            $this->backupLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            $backupConfig->update([
                'last_backup_result' => [
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'failed_at' => now()->toDateTimeString(),
                ],
            ]);

            // Limpiar conexión temporal
            DB::purge($this->tempConnectionName);

            Log::error('Error en backup', [
                'backup_config_id' => $backupConfig->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Crear o verificar que existe la base de datos destino
     */
    protected function createOrVerifyDatabase(): void
    {
        $config = $this->backupConfig->toConfigArray();
        
        // Conectar sin especificar base de datos
        $tempConfig = $config;
        unset($tempConfig['database']);
        
        Config::set("database.connections.{$this->tempConnectionName}_no_db", $tempConfig);
        DB::purge($this->tempConnectionName . '_no_db');
        
        $connection = DB::connection($this->tempConnectionName . '_no_db');
        
        // Verificar si la base de datos existe
        $databaseName = $this->backupConfig->database;
        $databaseExists = $connection->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
        
        if (empty($databaseExists)) {
            // Crear base de datos
            $charset = $config['charset'] ?? 'utf8mb4';
            $collation = $config['collation'] ?? 'utf8mb4_unicode_ci';
            
            $connection->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET {$charset} COLLATE {$collation}");
            
            Log::info('Base de datos creada', ['database' => $databaseName]);
        }
        
        DB::purge($this->tempConnectionName . '_no_db');
    }

    /**
     * Configurar conexión temporal
     */
    protected function setupTempConnection(): void
    {
        $config = $this->backupConfig->toConfigArray();
        Config::set("database.connections.{$this->tempConnectionName}", $config);
        DB::purge($this->tempConnectionName);
        
        // Verificar conexión
        DB::connection($this->tempConnectionName)->getPdo();
    }

    /**
     * Ejecutar migraciones en la BD destino
     */
    protected function runMigrations(): void
    {
        Log::info('Ejecutando migraciones en BD destino');
        
        // Guardar configuración actual
        $originalConnection = config('database.default');
        
        // Cambiar temporalmente la conexión por defecto
        Config::set('database.default', $this->tempConnectionName);
        
        try {
            // Ejecutar migraciones
            Artisan::call('migrate', [
                '--force' => true,
                '--database' => $this->tempConnectionName,
            ]);
            
            Log::info('Migraciones ejecutadas exitosamente');
        } finally {
            // Restaurar configuración original
            Config::set('database.default', $originalConnection);
        }
    }

    /**
     * Obtener lista de tablas a respaldar
     */
    protected function getTablesList(): void
    {
        $connection = DB::connection();
        $tables = $connection->select("SHOW TABLES");
        
        $tableKey = 'Tables_in_' . config('database.connections.' . config('database.default') . '.database');
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            // Incluir todas las tablas, incluyendo migrations
            $this->tablesToBackup[] = $tableName;
        }
        
        Log::info('Tablas identificadas para backup', [
            'count' => count($this->tablesToBackup),
            'tables' => $this->tablesToBackup,
        ]);
    }

    /**
     * Copiar datos de todas las tablas
     */
    protected function copyTablesData(): void
    {
        $sourceConnection = DB::connection();
        $targetConnection = DB::connection($this->tempConnectionName);
        
        $sourceDb = config('database.connections.' . config('database.default') . '.database');
        $tableKey = 'Tables_in_' . $sourceDb;
        
        foreach ($this->tablesToBackup as $tableName) {
            try {
                // Obtener estructura de la tabla
                $createTable = $sourceConnection->select("SHOW CREATE TABLE `{$tableName}`")[0];
                $createTableSql = $createTable->{'Create Table'};
                
                // Reemplazar nombre de BD si existe en el CREATE TABLE
                $createTableSql = str_replace("CREATE TABLE `{$tableName}`", "CREATE TABLE IF NOT EXISTS `{$tableName}`", $createTableSql);
                
                // Ejecutar CREATE TABLE en destino
                $targetConnection->statement($createTableSql);
                
                // Obtener datos en lotes para evitar problemas de memoria
                $totalRecords = $sourceConnection->table($tableName)->count();
                $batchSize = 1000;
                
                if ($totalRecords > 0) {
                    // Desactivar temporalmente foreign keys
                    $targetConnection->statement('SET FOREIGN_KEY_CHECKS=0');
                    
                    // Limpiar tabla destino antes de insertar
                    $targetConnection->statement("TRUNCATE TABLE `{$tableName}`");
                    
                    // Obtener columnas de la tabla
                    $columns = $sourceConnection->getSchemaBuilder()->getColumnListing($tableName);
                    $columnsStr = '`' . implode('`,`', $columns) . '`';
                    
                    // Insertar datos en lotes
                    $offset = 0;
                    while ($offset < $totalRecords) {
                        $records = $sourceConnection->table($tableName)
                            ->offset($offset)
                            ->limit($batchSize)
                            ->get();
                        
                        if ($records->isEmpty()) {
                            break;
                        }
                        
                        $values = [];
                        foreach ($records as $record) {
                            $recordArray = (array) $record;
                            $rowValues = [];
                            foreach ($columns as $column) {
                                $value = $recordArray[$column] ?? null;
                                if ($value === null) {
                                    $rowValues[] = 'NULL';
                                } else {
                                    $rowValues[] = $targetConnection->getPdo()->quote($value);
                                }
                            }
                            $values[] = '(' . implode(',', $rowValues) . ')';
                        }
                        
                        if (!empty($values)) {
                            $sql = "INSERT INTO `{$tableName}` ({$columnsStr}) VALUES " . implode(',', $values);
                            $targetConnection->statement($sql);
                        }
                        
                        $offset += $batchSize;
                    }
                    
                    // Reactivar foreign keys
                    $targetConnection->statement('SET FOREIGN_KEY_CHECKS=1');
                    
                    $this->recordsBackedUp += $totalRecords;
                }
                
                Log::info("Tabla {$tableName} respaldada", [
                    'records' => $totalRecords,
                ]);
                
                // Actualizar progreso
                $currentIndex = array_search($tableName, $this->tablesToBackup);
                $this->backupLog->update([
                    'tables_backed_up' => $currentIndex !== false ? $currentIndex + 1 : count($this->tablesToBackup),
                    'records_backed_up' => $this->recordsBackedUp,
                ]);
                
            } catch (Exception $e) {
                Log::warning("Error al respaldar tabla {$tableName}", [
                    'error' => $e->getMessage(),
                ]);
                // Continuar con la siguiente tabla
            }
        }
    }

    /**
     * Ejecutar seeders
     */
    protected function runSeeders(): void
    {
        Log::info('Ejecutando seeders en BD destino');
        
        // Guardar configuración actual
        $originalConnection = config('database.default');
        
        // Cambiar temporalmente la conexión por defecto
        Config::set('database.default', $this->tempConnectionName);
        
        try {
            // Ejecutar seeders
            Artisan::call('db:seed', [
                '--force' => true,
                '--database' => $this->tempConnectionName,
            ]);
            
            Log::info('Seeders ejecutados exitosamente');
        } catch (Exception $e) {
            Log::warning('Error al ejecutar seeders', [
                'error' => $e->getMessage(),
            ]);
            // No lanzar excepción, solo loggear
        } finally {
            // Restaurar configuración original
            Config::set('database.default', $originalConnection);
        }
    }
}

