<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('oauth_test_logs')) {
            Schema::create('oauth_test_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('oauth_provider_id')->constrained('oauth_providers')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('test_session_id')->unique(); // ID único para identificar la prueba
                $table->enum('step', [
                    'redirect_generated',      // URL de autorización generada
                    'user_clicked',           // Usuario hizo clic en "Abrir en nueva pestaña"
                    'oauth_authorized',       // Usuario autorizó en Google/Facebook
                    'callback_received',      // Callback recibido del provider
                    'user_data_retrieved',    // Datos del usuario obtenidos
                    'user_created',           // Usuario creado en BD
                    'user_logged_in',         // Sesión creada
                    'redirected_to_dashboard', // Redirigido al dashboard
                    'session_verified',       // Sesión verificada
                    'completed',              // Flujo completo
                    'error'                   // Error en algún paso
                ]);
                $table->text('step_data')->nullable(); // JSON con datos del paso
                $table->text('error_message')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                
                $table->index('test_session_id');
                $table->index('oauth_provider_id');
                $table->index('step');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oauth_test_logs');
    }
};

