<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequirementLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_requirement_id',
        'user_id',
        'accion',
        'valores_anteriores',
        'valores_nuevos',
        'motivo',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    /**
     * Relación: Un log pertenece a un requisito
     */
    public function documentRequirement()
    {
        return $this->belongsTo(DocumentRequirement::class);
    }

    /**
     * Relación: Un log pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
