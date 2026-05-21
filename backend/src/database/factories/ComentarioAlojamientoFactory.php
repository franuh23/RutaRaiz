<?php

namespace Database\Factories;

use App\Models\ComentarioAlojamiento;
use App\Models\Alojamiento;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComentarioAlojamiento>
 */
class ComentarioAlojamientoFactory extends Factory
{
    protected $model = ComentarioAlojamiento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'alojamiento_id' => Alojamiento::factory(),
            'texto' => fake()->paragraph(),
            'activo' => true,
        ];
    }
}
