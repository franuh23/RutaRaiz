<?php

namespace Database\Factories;

use App\Models\ComentarioLocalizacion;
use App\Models\Localizacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComentarioLocalizacion>
 */
class ComentarioLocalizacionFactory extends Factory
{
    protected $model = ComentarioLocalizacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'usuario_id' => Usuario::factory(),
            'localizacion_id' => Localizacion::factory(),
            'texto' => fake()->paragraph(),
            'activo' => true,
        ];
    }
}
