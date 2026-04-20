<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Localizacion extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ruta_id'
        'nombre',
        'distancia_desde_inicio',
        'distancia_desde_fin',
        'descripcion',
        'activo',
    ];

    protected $table = 'localizaciones';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'distancia_desde_inicio' => 'decimal:2',
            'distancia_desde_fin' => 'decimal:2',
        ];
    }

    // Relación, pertenece a una ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    // Relación, tiene muchos alojamientos
    public function alojamientos()
    {
        return $this->hasMany(Alojamiento::class);
    }

    // Relación, tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(ComentarioLocalizacion::class);
    }

    // Relación, como punto de inicio en planificaciones
    public function planificacionesInicio()
    {
        return $this->hasMany(Planificacion::class, 'localizacion_inicio_id');
    }

    // Relación, como punto de fin en planificaciones
    public function planificacionesFin()
    {
        return $this->hasMany(Planificacion::class, 'localizacion_fin_id');
    }
}
