<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DatabaseConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;

class DatabaseConfigEnvUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica que updateEnvVariable reemplaza correctamente las variables
     */
    public function test_update_env_variable_replaces_existing_variable(): void
    {
        $config = new DatabaseConfig();
        
        $content = "DB_HOST=127.0.0.1\nDB_DATABASE=test_db";
        $result = $this->invokeProtected($config, 'updateEnvVariable', [$content, 'DB_HOST', '192.168.1.1']);
        
        $this->assertStringContainsString('DB_HOST=192.168.1.1', $result);
        $this->assertStringNotContainsString('DB_HOST=127.0.0.1', $result);
    }

    /**
     * Test que verifica que escapeEnvValue escapa valores con espacios
     */
    public function test_escape_env_value_handles_spaces(): void
    {
        $config = new DatabaseConfig();
        
        // Valor con espacios
        $result = $this->invokeProtected($config, 'escapeEnvValue', ['value with spaces']);
        $this->assertEquals('"value with spaces"', $result);
        
        // Valor sin espacios
        $result = $this->invokeProtected($config, 'escapeEnvValue', ['simple_value']);
        $this->assertEquals('simple_value', $result);
        
        // Valor con comillas
        $result = $this->invokeProtected($config, 'escapeEnvValue', ['value "with" quotes']);
        $this->assertEquals('"value \\"with\\" quotes"', $result);
    }

    /**
     * Test que verifica que updateEnvVariable maneja variables comentadas
     */
    public function test_update_env_variable_handles_commented_variables(): void
    {
        $config = new DatabaseConfig();
        
        $content = "# DB_HOST=127.0.0.1\nDB_DATABASE=test_db";
        $result = $this->invokeProtected($config, 'updateEnvVariable', [$content, 'DB_HOST', '192.168.1.1']);
        
        // Debe descomentar y actualizar
        $this->assertStringContainsString('DB_HOST=192.168.1.1', $result);
        $this->assertStringNotContainsString('# DB_HOST=127.0.0.1', $result);
    }

    /**
     * Test que verifica que updateEnvVariable agrega variables nuevas
     */
    public function test_update_env_variable_adds_new_variable(): void
    {
        $config = new DatabaseConfig();
        
        $content = "DB_DATABASE=test_db";
        $result = $this->invokeProtected($config, 'updateEnvVariable', [$content, 'DB_HOST', '192.168.1.1']);
        
        $this->assertStringContainsString('DB_HOST=192.168.1.1', $result);
        $this->assertStringContainsString('DB_DATABASE=test_db', $result);
    }

    private function invokeProtected(object $object, string $method, array $arguments): mixed
    {
        return (new ReflectionMethod($object, $method))->invoke($object, ...$arguments);
    }
}
