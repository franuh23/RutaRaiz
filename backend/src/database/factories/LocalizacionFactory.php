<?php

namespace Database\Factories;

use App\Models\Localizacion;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Localizacion>
 */
class LocalizacionFactory extends Factory
{
    protected $model = Localizacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->city(),
            'distancia_desde_inicio' => fake()->randomFloat(1, 0, 800),
            'distancia_desde_fin' => fake()->randomFloat(1, 0, 800),
            'descripcion' => fake()->optional()->sentence(),
            'activo' => true,
        ];
    }
}
