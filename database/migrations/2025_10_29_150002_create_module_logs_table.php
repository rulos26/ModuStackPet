<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('module_logs')) {
            Schema::create('module_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
                $table->string('action'); // activated, deactivated, access_denied, permission_changed, etc.
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('timestamp')->useCurrent();
                $table->index(['module_id', 'action']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('module_logs');
    }
};




