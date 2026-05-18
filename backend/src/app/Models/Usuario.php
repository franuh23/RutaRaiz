<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nick',
        'nombre',
        'apellidos',
        'rol',
        'email',
        'password',
        'avatar',
        'fecha_nacimiento',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'usuarios';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'fecha_registro' => 'datetime',
        ];
    }

    // Relación, un usuario tiene muchas planificaciones
    public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }

    // Relación, un usuario tiene muchos comentarios sobre la planificación
    public function comentariosPlanificacion()
    {
        return $this->hasMany(ComentarioPlanificacion::class);
    }

    // Relación, un usuario tiene muchos comentarios sobre la ruta
    public function comentariosRuta()
    {
        return $this->hasMany(ComentarioRuta::class);
    }

    // Relación, un usuario tiene muchos comentarios sobre una localización
    public function comentariosLocalizacion()
    {
        return $this->hasMany(ComentarioLocalizacion::class);
    }

    // Relación, un usuario tiene muchos comentarios sobre un alojamiento
    public function comentariosAlojamiento()
    {
        return $this->hasMany(ComentarioAlojamiento::class);
    }
}
