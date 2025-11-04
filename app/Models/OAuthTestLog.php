<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthTestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'oauth_provider_id',
        'user_id',
        'test_session_id',
        'step',
        'step_data',
        'error_message',
        'ip_address',
        'user_agent',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'step_data' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function oauthProvider(): BelongsTo
    {
        return $this->belongsTo(OAuthProvider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
