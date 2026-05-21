<?php

namespace Database\Factories;

use App\Models\ComentarioRuta;
use App\Models\Ruta;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComentarioRuta>
 */
class ComentarioRutaFactory extends Factory
{
    protected $model = ComentarioRuta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'ruta_id' => Ruta::factory(),
            'texto' => fake()->paragraph(),
            'activo' => true,
        ];
    }
}
