<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Ruta extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'dificultad',
        'inicio',
        'fin',
        'kilometros',
        'imagen',
        'activo',
    ];

    protected $table = 'rutas';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'kilometros' => 'decimal:2',
        ];
    }

    // Relación, una ruta tiene muchas localizaciones
    public function localizaciones()
    {
        return $this->hasMany(Localizacion::class);
    }

    // Relación, una ruta tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(ComentarioRuta::class);
    }

    // Relación, una ruta tiene muchas planificaciones
    public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
}
