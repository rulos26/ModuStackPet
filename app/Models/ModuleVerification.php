<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class ModuleVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
        'verification_code',
        'action',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a 6-digit verification code
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if verification is valid and not expired
     */
    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    /**
     * Mark verification as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Clean up expired verifications
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }

    /**
     * Create a new verification for module action
     */
    public static function createForModule(int $userId, int $moduleId, string $action): self
    {
        // Clean up any existing verifications for this user/module/action
        self::where('user_id', $userId)
            ->where('module_id', $moduleId)
            ->where('action', $action)
            ->whereNull('used_at')
            ->delete();

        return self::create([
            'user_id' => $userId,
            'module_id' => $moduleId,
            'verification_code' => self::generateCode(),
            'action' => $action,
            'expires_at' => now()->addMinutes(10), // 10 minutes expiration
        ]);
    }

    /**
     * Find valid verification by code
     */
    public static function findByCode(string $code, int $userId): ?self
    {
        return self::where('verification_code', $code)
            ->where('user_id', $userId)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
