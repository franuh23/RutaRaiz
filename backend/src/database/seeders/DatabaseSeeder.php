<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;
use App\Models\ComentarioRuta;
use App\Models\ComentarioLocalizacion;
use App\Models\ComentarioAlojamiento;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder del Camino primitivo con localizaciones reales
        $this->call(CaminoPrimitivoSeeder::class);

        // 10 usuarios para utilizarlos después
        $usuarios = Usuario::factory(10)->create();

        // Rutas, localizaciones y alojamientos vinculados a los 10 usuarios
        Ruta::factory(5)
            ->has(
                Localizacion::factory(fake()->numberBetween(10, 50))
                    ->has(Alojamiento::factory(fake()->numberBetween(1, 5)), 'alojamientos'),'localizaciones')
            ->create();

        // Comentarios fake de rutas
        Ruta::all()->each(function ($ruta) use ($usuarios) {
            ComentarioRuta::factory(3)->create([
                'ruta_id' => $ruta->id,
                'usuario_id' => $usuarios->random()->id
            ]);
        });

        // Comentarios fake de localizaciones
        Localizacion::all()->each(function ($localizacion) use ($usuarios) {
            ComentarioLocalizacion::factory(2)->create([
                'localizacion_id' => $localizacion->id,
                'usuario_id' => $usuarios->random()->id
            ]);
        });

        // Comentarios fake de alojamientos
        Alojamiento::all()->each(function ($alojamiento) use ($usuarios) {
            ComentarioAlojamiento::factory(2)->create([
                'alojamiento_id' => $alojamiento->id,
                'usuario_id' => $usuarios->random()->id
            ]);
        });
    }
}
