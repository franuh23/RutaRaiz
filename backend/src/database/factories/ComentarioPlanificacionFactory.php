<?php

namespace Database\Factories;

use App\Models\ComentarioPlanificacion;
use App\Models\Planificacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComentarioPlanificacion>
 */
class ComentarioPlanificacionFactory extends Factory
{
    protected $model = ComentarioPlanificacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'planificacion_id' => Planificacion::factory(),
            'texto' => fake()->paragraph(),
            'activo' => true,
        ];
    }
}
