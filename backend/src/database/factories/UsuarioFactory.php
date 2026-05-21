<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nick' => fake()->unique()->userName(),
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'rol' => 'usuario',
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'avatar' => null,
            'fecha_nacimiento' => fake()->optional()->date(),
            'activo' => true,
        ];
    }
}
