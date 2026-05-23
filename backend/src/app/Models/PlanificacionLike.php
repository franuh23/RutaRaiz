<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanificacionLike extends Model
{
    protected $table = 'planificacion_likes';

    protected $fillable = [
        'usuario_id',
        'planificacion_id',
    ];

    // Relación: El like pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación: El like pertenece a una planificación
    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'planificacion_id');
    }
}
