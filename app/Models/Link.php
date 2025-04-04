<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Link
 *
 * @property $id
 * @property $link
 * @property $nombre
 * @property $id_modulo
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Link extends Model
{
    
    protected $perPage = 60;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['link', 'nombre', 'id_modulo'];

    public function modulo(){
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }


}
