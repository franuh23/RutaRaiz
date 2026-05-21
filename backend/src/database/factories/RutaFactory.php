<?php

namespace Database\Factories;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ruta>
 */
class RutaFactory extends Factory
{
    protected $model = Ruta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'dificultad' => fake()->randomElement(['baja', 'media', 'alta']),
            'inicio' => fake()->city(),
            'fin' => fake()->city(),
            'kilometros' => fake()->randomFloat(1, 10, 800),
            'imagen' => null,
            'activo' => true,
        ];
    }
}
