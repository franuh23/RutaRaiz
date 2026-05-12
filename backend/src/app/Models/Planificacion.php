<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Planificacion extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'usuario_id',
        'ruta_id',
        'localizacion_inicio_id',
        'localizacion_fin_id',
        'fecha_inicio',
        'km_dia',
        'dias_totales',
        'activo',
    ];

    protected $table = 'planificacion';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_inicio' => 'date',
            'km_dia' => 'decimal:2',
            'dias_totales' => 'integer',
        ];
    }

    // Relación, pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación, pertenece a una ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    // Relación, pertenece a una localización (inicio)
    public function localizacionInicio()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_inicio_id');
    }

    // Relación, pertenece a una localización (fin)
    public function localizacionFin()
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_fin_id');
    }

    // Relación, tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(ComentarioPlanificacion::class);
    }
}
