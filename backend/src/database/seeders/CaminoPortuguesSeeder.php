<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CaminoPortuguesSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino Portugués Central'],
            [
                'dificultad' => 'media',
                'inicio' => 'Lisboa',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 610,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Mapa_Camino_Portugu%C3%A9s.png/800px-Mapa_Camino_Portugu%C3%A9s.png',
                'activo' => true
            ]
        );

        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            // PORTUGAL
            ['nombre' => 'Lisboa', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 610, 'descripcion' => 'Capital de Portugal. Inicio del Camino Portugués.'],
            ['nombre' => 'Santa Iria de Azóia', 'distancia_desde_inicio' => 18.0, 'distancia_desde_fin' => 592],
            ['nombre' => 'Alverca do Ribatejo', 'distancia_desde_inicio' => 28.0, 'distancia_desde_fin' => 582],
            ['nombre' => 'Vila Franca de Xira', 'distancia_desde_inicio' => 36.0, 'distancia_desde_fin' => 574],
            ['nombre' => 'Azambuja', 'distancia_desde_inicio' => 56.0, 'distancia_desde_fin' => 554],
            ['nombre' => 'Santarem', 'distancia_desde_inicio' => 85.0, 'distancia_desde_fin' => 525, 'descripcion' => 'Ciudad gótica sobre el río Tajo.'],
            ['nombre' => 'Golegã', 'distancia_desde_inicio' => 112.0, 'distancia_desde_fin' => 498],
            ['nombre' => 'Tomar', 'distancia_desde_inicio' => 140.0, 'distancia_desde_fin' => 470, 'descripcion' => 'Convento de Cristo, Patrimonio de la Humanidad.'],
            ['nombre' => 'Alvaiazere', 'distancia_desde_inicio' => 170.0, 'distancia_desde_fin' => 440],
            ['nombre' => 'Coimbra', 'distancia_desde_inicio' => 200.0, 'distancia_desde_fin' => 410, 'descripcion' => 'Ciudad universitaria, famosa por su Universidad y Fado.'],
            ['nombre' => 'Mealhada', 'distancia_desde_inicio' => 230.0, 'distancia_desde_fin' => 380],
            ['nombre' => 'Águeda', 'distancia_desde_inicio' => 260.0, 'distancia_desde_fin' => 350],
            ['nombre' => 'Albergaria-a-Velha', 'distancia_desde_inicio' => 290.0, 'distancia_desde_fin' => 320],
            ['nombre' => 'Oliveira de Azeméis', 'distancia_desde_inicio' => 320.0, 'distancia_desde_fin' => 290],
            ['nombre' => 'Porto', 'distancia_desde_inicio' => 350.0, 'distancia_desde_fin' => 260, 'descripcion' => 'Capital del norte. Famosa por su río Duero y el vino.'],

            // ESPAÑA (GALICIA)
            ['nombre' => 'Tui', 'distancia_desde_inicio' => 390.0, 'distancia_desde_fin' => 220, 'descripcion' => 'Entrada a Galicia. Catedral y casco histórico.'],
            ['nombre' => 'O Porriño', 'distancia_desde_inicio' => 410.0, 'distancia_desde_fin' => 200],
            ['nombre' => 'Redondela', 'distancia_desde_inicio' => 430.0, 'distancia_desde_fin' => 180],
            ['nombre' => 'Pontevedra', 'distancia_desde_inicio' => 460.0, 'distancia_desde_fin' => 150, 'descripcion' => 'Ciudad histórica con ruinas romanas.'],
            ['nombre' => 'Caldas de Reis', 'distancia_desde_inicio' => 490.0, 'distancia_desde_fin' => 120],
            ['nombre' => 'Padrón', 'distancia_desde_inicio' => 520.0, 'distancia_desde_fin' => 90, 'descripcion' => 'Pedrón, lugar donde llegó la barca del Apóstol Santiago.'],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 610.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del Camino. Catedral del Apóstol Santiago.'],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Lisboa
            ['localizacion' => 'Lisboa', 'nombre' => 'Albergue de Peregrinos Lisboa', 'tipo' => 'albergue', 'telefono' => '+351 218 870 300'],
            ['localizacion' => 'Lisboa', 'nombre' => 'Hostal Lisboa Central', 'tipo' => 'hostal', 'telefono' => '+351 218 870 301'],

            // Santarem
            ['localizacion' => 'Santarem', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+351 243 556 000'],

            // Tomar
            ['localizacion' => 'Tomar', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+351 249 322 000'],
            ['localizacion' => 'Tomar', 'nombre' => 'Hotel República', 'tipo' => 'hotel', 'telefono' => '+351 249 322 001'],

            // Coimbra
            ['localizacion' => 'Coimbra', 'nombre' => 'Albergue de Santa Clara', 'tipo' => 'albergue', 'telefono' => '+351 239 833 000'],
            ['localizacion' => 'Coimbra', 'nombre' => 'Hotel Oslo', 'tipo' => 'hotel', 'telefono' => '+351 239 833 001'],

            // Porto
            ['localizacion' => 'Porto', 'nombre' => 'Albergue de Peregrinos Porto', 'tipo' => 'albergue', 'telefono' => '+351 222 073 800'],
            ['localizacion' => 'Porto', 'nombre' => 'Hotel São José', 'tipo' => 'hotel', 'telefono' => '+351 222 073 801'],

            // Tui
            ['localizacion' => 'Tui', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 986 600 000'],
            ['localizacion' => 'Tui', 'nombre' => 'Hotel As Torres', 'tipo' => 'hotel', 'telefono' => '+34 986 600 001'],

            // Pontevedra
            ['localizacion' => 'Pontevedra', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+34 986 850 000'],
            ['localizacion' => 'Pontevedra', 'nombre' => 'Hotel Ruas', 'tipo' => 'hotel', 'telefono' => '+34 986 850 001'],

            // Caldas de Reis
            ['localizacion' => 'Caldas de Reis', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 986 540 000'],

            // Padrón
            ['localizacion' => 'Padrón', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+34 981 810 000'],

            // Santiago
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Albergue Seminario Menor', 'tipo' => 'albergue', 'telefono' => '+34 981 563 810', 'plazas' => 300],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Parador de los Reyes Católicos', 'tipo' => 'hotel', 'telefono' => '+34 981 582 200'],
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

        $this->command->info('Camino Portugués creado con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
