<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CaminoInglesSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino Inglés'],
            [
                'dificultad' => 'baja',
                'inicio' => 'Ferrol',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 110,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/Camino_Ingl%C3%A9s_route_map.svg/800px-Camino_Ingl%C3%A9s_route_map.svg.png',
                'activo' => true
            ]
        );

        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            ['nombre' => 'Ferrol', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 110, 'descripcion' => 'Puerto naval. Inicio del Camino Inglés.'],
            ['nombre' => 'Neda', 'distancia_desde_inicio' => 12.0, 'distancia_desde_fin' => 98],
            ['nombre' => 'Pontedeume', 'distancia_desde_inicio' => 22.0, 'distancia_desde_fin' => 88, 'descripcion' => 'Puente medieval sobre el río Eume.'],
            ['nombre' => 'Betanzos', 'distancia_desde_inicio' => 36.0, 'distancia_desde_fin' => 74, 'descripcion' => 'Ciudad medieval con iglesias góticas.'],
            ['nombre' => 'Presedo', 'distancia_desde_inicio' => 48.0, 'distancia_desde_fin' => 62],
            ['nombre' => 'Coirós', 'distancia_desde_inicio' => 52.0, 'distancia_desde_fin' => 58],
            ['nombre' => 'Arzúa', 'distancia_desde_inicio' => 67.0, 'distancia_desde_fin' => 43, 'descripcion' => 'Cruce con el Camino Francés.'],
            ['nombre' => 'O Pedrouzo', 'distancia_desde_inicio' => 85.0, 'distancia_desde_fin' => 25],
            ['nombre' => 'Monte do Gozo', 'distancia_desde_inicio' => 105.0, 'distancia_desde_fin' => 5],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 110.0, 'distancia_desde_fin' => 0],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Ferrol
            ['localizacion' => 'Ferrol', 'nombre' => 'Albergue de Peregrinos Ferrol', 'tipo' => 'albergue', 'telefono' => '+34 881 930 000', 'plazas' => 80],
            ['localizacion' => 'Ferrol', 'nombre' => 'Hotel Silva', 'tipo' => 'hotel', 'telefono' => '+34 981 350 000'],

            // Neda
            ['localizacion' => 'Neda', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 981 400 000', 'plazas' => 40],

            // Pontedeume
            ['localizacion' => 'Pontedeume', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+34 981 430 000', 'plazas' => 50],
            ['localizacion' => 'Pontedeume', 'nombre' => 'Hotel Eume', 'tipo' => 'hotel', 'telefono' => '+34 981 430 001'],

            // Betanzos
            ['localizacion' => 'Betanzos', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+34 981 770 000', 'plazas' => 60],
            ['localizacion' => 'Betanzos', 'nombre' => 'Hotel Garelos', 'tipo' => 'hotel', 'telefono' => '+34 981 770 001'],

            // Presedo
            ['localizacion' => 'Presedo', 'nombre' => 'Albergue O Cruceiro', 'tipo' => 'albergue', 'telefono' => '+34 981 780 000', 'plazas' => 30],

            // Arzúa
            ['localizacion' => 'Arzúa', 'nombre' => 'Albergue Don Quijote', 'tipo' => 'albergue', 'telefono' => '+34 981 507 920', 'plazas' => 70],
            ['localizacion' => 'Arzúa', 'nombre' => 'Pensión Luis', 'tipo' => 'hostal', 'telefono' => '+34 981 500 125'],

            // O Pedrouzo
            ['localizacion' => 'O Pedrouzo', 'nombre' => 'Albergue O Pedrouzo', 'tipo' => 'albergue', 'telefono' => '+34 981 511 178', 'plazas' => 100],

            // Monte do Gozo
            ['localizacion' => 'Monte do Gozo', 'nombre' => 'Albergue Monte do Gozo', 'tipo' => 'albergue', 'telefono' => '+34 981 558 958', 'plazas' => 400],

            // Santiago
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Albergue Seminario Menor', 'tipo' => 'albergue', 'telefono' => '+34 981 563 810', 'plazas' => 300],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Hostal de los Reyes Católicos', 'tipo' => 'hostal', 'telefono' => '+34 981 582 200'],
        ];

        foreach ($alojamientos as $item) {
            $localizacion = $localizacionesMap[$item['localizacion']] ?? null;
            if ($localizacion) {
                Alojamiento::create([
                    'localizacion_id' => $localizacion->id,
                    'nombre' => $item['nombre'],
                    'tipo' => $item['tipo'],
                    'telefono' => $item['telefono'],
                    'activo' => true,
                ]);
            }
        }

        $this->command->info('Camino Inglés creado con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
