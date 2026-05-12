<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $fillable = [
        'planificacion_id',
        'dia',
        'localizacion_inicio_id',
        'localizacion_fin_id',
        'distancia',
    ];

    protected $table = 'etapas';

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class);
    }

    public function localizacionInicio()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_inicio_id');
    }

    public function localizacionFin()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_fin_id');
    }
}
