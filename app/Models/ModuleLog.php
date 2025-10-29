<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleLog extends Model
{
    use HasFactory;

    // Deshabilitar timestamps automáticos de Laravel
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'module_id',
        'action',
        'ip_address',
        'user_agent',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    /**
     * Log actions
     */
    const ACTION_ACTIVATED = 'activated';
    const ACTION_DEACTIVATED = 'deactivated';
    const ACTION_ACCESS_DENIED = 'access_denied';
    const ACTION_PERMISSION_CHANGED = 'permission_changed';
    const ACTION_VERIFICATION_SENT = 'verification_sent';
    const ACTION_VERIFICATION_FAILED = 'verification_failed';

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Create a log entry
     */
    public static function createLog(
        int $userId,
        int $moduleId,
        string $action,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'module_id' => $moduleId,
            'action' => $action,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Get logs for a specific module
     */
    public static function getModuleLogs(int $moduleId, int $limit = 50)
    {
        return self::with(['user', 'module'])
            ->where('module_id', $moduleId)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent logs across all modules
     */
    public static function getRecentLogs(int $limit = 100)
    {
        return self::with(['user', 'module'])
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get logs by action type
     */
    public static function getLogsByAction(string $action, int $limit = 50)
    {
        return self::with(['user', 'module'])
            ->where('action', $action)
            ->orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get access denied attempts
     */
    public static function getAccessDeniedLogs(int $limit = 50)
    {
        return self::getLogsByAction(self::ACTION_ACCESS_DENIED, $limit);
    }

    /**
     * Get failed verification attempts
     */
    public static function getFailedVerificationLogs(int $limit = 50)
    {
        return self::getLogsByAction(self::ACTION_VERIFICATION_FAILED, $limit);
    }

    /**
     * Scope for specific actions
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific module
     */
    public function scopeModule($query, int $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    /**
     * Scope for specific user
     */
    public function scopeUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('timestamp', '>=', now()->subDays($days));
    }
}
