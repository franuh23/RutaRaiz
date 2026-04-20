<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Alojamiento extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'localizacion_id',
        'nombre',
        'direccion',
        'tipo',
        'enlace',
        'telefono',
        'email',
        'imagen',
        'activo',
    ];

    protected $table = 'alojamientos';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    // Relación, pertenece a una localización
    public function localizacion()
    {
        return $this->belongsTo(Localizacion::class);
    }

    // Relación, tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(ComentarioAlojamiento::class);
    }
}
