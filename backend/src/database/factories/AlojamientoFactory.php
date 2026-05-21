<?php

namespace Database\Factories;

use App\Models\Alojamiento;
use App\Models\Localizacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alojamiento>
 */
class AlojamientoFactory extends Factory
{
    protected $model = Alojamiento::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'localizacion_id' => Localizacion::factory(),
            'nombre' => fake()->company(),
            'direccion' => fake()->optional()->streetAddress(),
            'tipo' => fake()->randomElement(['hostal', 'hotel', 'albergue', 'casa_rural', 'camping']),
            'enlace' => fake()->optional()->url(),
            'telefono' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'imagen' => null,
            'activo' => true,
        ];
    }
}
