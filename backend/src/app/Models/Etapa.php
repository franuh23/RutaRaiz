<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'planificacion_id',
        'dia',
        'localizacion_inicio_id',
        'localizacion_fin_id',
        'distancia',
        'completada',
    ];

    protected $table = 'etapas';

    // Relación, pertenecea a una planificación
    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class);
    }

    // Relación, pertenece a una localización de inicio
    public function localizacionInicio()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_inicio_id');
    }


    // Relación, pertenece a una localización de fin
    public function localizacionFin()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_fin_id');
    }
}
