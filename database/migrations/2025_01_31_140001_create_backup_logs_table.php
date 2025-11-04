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
        if (!Schema::hasTable('backup_logs')) {
            Schema::create('backup_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('backup_config_id')->constrained('backup_configs')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
                $table->integer('tables_backed_up')->default(0);
                $table->integer('tables_total')->default(0);
                $table->bigInteger('records_backed_up')->default(0);
                $table->text('error_message')->nullable();
                $table->text('details')->nullable()->comment('Detalles adicionales en JSON');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
                
                $table->index('backup_config_id');
                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};

