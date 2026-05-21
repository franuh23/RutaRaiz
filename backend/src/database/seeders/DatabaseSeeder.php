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

        // Usuarios fake
        Usuario::factory(10)->create();

        // 5 rutas fake con localizaciones y alojamientos
        Ruta::factory(5)
            ->has(Localizacion::factory(fake()->numberBetween(10, 50))
            ->has(Alojamiento::factory(fake()->numberBetween(1, 5)), 'alojamientos'),'localizaciones')
        ->create();

        // Comentarios fake de rutas
        Ruta::all()->each(function ($ruta) {
            ComentarioRuta::factory(3)->create(['ruta_id' => $ruta->id]);
        });

        // Comentarios fake de localizaciones
        Localizacion::all()->each(function ($localizacion) {
            ComentarioLocalizacion::factory(2)->create(['localizacion_id' => $localizacion->id]);
        });

        // Comentarios fake de alojamientos
        Alojamiento::all()->each(function ($alojamiento) {
            ComentarioAlojamiento::factory(2)->create(['alojamiento_id' => $alojamiento->id]);
        });
    }
}
