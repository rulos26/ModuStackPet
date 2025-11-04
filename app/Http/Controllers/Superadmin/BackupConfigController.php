<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BackupConfig;
use App\Models\BackupLog;
use App\Services\BackupService;
use App\Notifications\BackupCompletedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BackupConfigController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $configs = BackupConfig::with('backupLogs')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $productionDb = BackupConfig::getProductionDatabaseName();
        
        return view('superadmin.backup-configs.index', compact('configs', 'productionDb'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $config = new BackupConfig();
        $productionDb = BackupConfig::getProductionDatabaseName();
        
        return view('superadmin.backup-configs.create', compact('config', 'productionDb'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'connection' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'execute_seeders' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Validar que no sea la BD de producción
        $productionDb = BackupConfig::getProductionDatabaseName();
        if ($productionDb !== null && $validated['database'] === $productionDb) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se puede usar la misma base de datos de producción como destino.');
        }

        $config = BackupConfig::create($validated);

        Log::info('Configuración de Backup creada', [
            'id' => $config->id,
            'name' => $config->name,
            'database' => $config->database,
        ]);

        return redirect()
            ->route('superadmin.backup-configs.index')
            ->with('success', 'Configuración de backup creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BackupConfig $backupConfig): View
    {
        $productionDb = BackupConfig::getProductionDatabaseName();
        
        return view('superadmin.backup-configs.edit', compact('backupConfig', 'productionDb'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BackupConfig $backupConfig): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'connection' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'execute_seeders' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Si no se proporciona password, mantener el actual
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Validar que no sea la BD de producción
        $productionDb = BackupConfig::getProductionDatabaseName();
        if ($productionDb !== null && $validated['database'] === $productionDb) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se puede usar la misma base de datos de producción como destino.');
        }

        $backupConfig->update($validated);

        Log::info('Configuración de Backup actualizada', [
            'id' => $backupConfig->id,
            'name' => $backupConfig->name,
        ]);

        return redirect()
            ->route('superadmin.backup-configs.index')
            ->with('success', 'Configuración de backup actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BackupConfig $backupConfig): RedirectResponse
    {
        $backupConfig->delete();

        Log::info('Configuración de Backup eliminada', [
            'id' => $backupConfig->id,
        ]);

        return redirect()
            ->route('superadmin.backup-configs.index')
            ->with('success', 'Configuración eliminada exitosamente.');
    }

    /**
     * Ejecutar backup
     */
    public function execute(Request $request, BackupConfig $backupConfig): RedirectResponse
    {
        try {
            // Validar que no sea la BD de producción
            if ($backupConfig->isProductionDatabase()) {
                return redirect()
                    ->route('superadmin.backup-configs.index')
                    ->with('error', 'No se puede ejecutar backup a la base de datos de producción.');
            }

            // Validar configuración
            if (!$backupConfig->isConfigured()) {
                return redirect()
                    ->route('superadmin.backup-configs.index')
                    ->with('error', 'La configuración de backup no está completa.');
            }

            // Ejecutar backup en segundo plano (puede tardar)
            $backupLog = $this->backupService->executeBackup($backupConfig, Auth::id());

            // Enviar notificación a superadmins
            $superadmins = \App\Models\User::role('Superadmin')->get();
            foreach ($superadmins as $superadmin) {
                $superadmin->notify(new BackupCompletedNotification($backupLog));
            }

            return redirect()
                ->route('superadmin.backup-configs.index')
                ->with('success', 'Backup iniciado exitosamente. Se enviará una notificación al completar.');

        } catch (\Exception $e) {
            Log::error('Error al ejecutar backup', [
                'backup_config_id' => $backupConfig->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('superadmin.backup-configs.index')
                ->with('error', 'Error al ejecutar backup: ' . $e->getMessage());
        }
    }

    /**
     * Ver logs de backup
     */
    public function logs(BackupConfig $backupConfig): View
    {
        $logs = BackupLog::where('backup_config_id', $backupConfig->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('superadmin.backup-configs.logs', compact('backupConfig', 'logs'));
    }
}
