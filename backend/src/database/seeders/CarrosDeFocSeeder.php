<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CarrosDeFocSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Carros de Foc · Travesía Circular'],
            [
                'dificultad' => 'alta',
                'inicio' => 'Espot',
                'fin' => 'Espot',
                'kilometros' => 65,
                'imagen' => 'https://www.carrosfoc.cat/wp-content/uploads/2019/01/logocarrosfoc.png',
                'activo' => true
            ]
        );

        // Limpiar datos antiguos
        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            ['nombre' => 'Espot', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 65, 'descripcion' => 'Pueblo de montaña, puerta de entrada al Parque Nacional de Aigüestortes.'],
            ['nombre' => 'Refugi d\'Amitges', 'distancia_desde_inicio' => 8.0, 'distancia_desde_fin' => 57, 'descripcion' => 'Refugio de montaña a 2.380 m, junto al lago del mismo nombre.'],
            ['nombre' => 'Refugi de Saboredo', 'distancia_desde_inicio' => 17.0, 'distancia_desde_fin' => 48, 'descripcion' => 'Refugio situado en el corazón del circo de Saboredo.'],
            ['nombre' => 'Refugi de Colomers', 'distancia_desde_inicio' => 24.0, 'distancia_desde_fin' => 41, 'descripcion' => 'En la Vall de Colomers, rodeado de más de 50 lagos.'],
            ['nombre' => 'Refugi de la Restanca', 'distancia_desde_inicio' => 32.0, 'distancia_desde_fin' => 33, 'descripcion' => 'Uno de los refugios más emblemáticos del Pirineo.'],
            ['nombre' => 'Refugi de Ventosa i Calvell', 'distancia_desde_inicio' => 40.0, 'distancia_desde_fin' => 25, 'descripcion' => 'En el valle de Colieto, junto al lago de Travessani.'],
            ['nombre' => 'Refugi de la Conca', 'distancia_desde_inicio' => 48.0, 'distancia_desde_fin' => 17, 'descripcion' => 'Situado en el valle de San Nicolau.'],
            ['nombre' => 'Refugi de Josep M. Blanc', 'distancia_desde_inicio' => 56.0, 'distancia_desde_fin' => 9, 'descripcion' => 'En el corazón del Parque Nacional, junto al lago Llong.'],
            ['nombre' => 'Espot', 'distancia_desde_inicio' => 65.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del recorrido circular.'],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        // Obtener IDs de localizaciones
        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS (REFUGIOS) ====================
        $alojamientos = [
            ['localizacion' => 'Espot', 'nombre' => 'Hotel Sant Maurici', 'tipo' => 'hotel', 'telefono' => '+34 973 624 210'],
            ['localizacion' => 'Espot', 'nombre' => 'Refugi d\'Espot', 'tipo' => 'albergue', 'telefono' => '+34 973 624 211', 'plazas' => 50],
            ['localizacion' => 'Refugi d\'Amitges', 'nombre' => 'Refugi d\'Amitges', 'tipo' => 'albergue', 'telefono' => '+34 973 624 212', 'plazas' => 80],
            ['localizacion' => 'Refugi de Saboredo', 'nombre' => 'Refugi de Saboredo', 'tipo' => 'albergue', 'telefono' => '+34 973 624 213', 'plazas' => 60],
            ['localizacion' => 'Refugi de Colomers', 'nombre' => 'Refugi de Colomers', 'tipo' => 'albergue', 'telefono' => '+34 973 624 214', 'plazas' => 70],
            ['localizacion' => 'Refugi de la Restanca', 'nombre' => 'Refugi de la Restanca', 'tipo' => 'albergue', 'telefono' => '+34 973 624 215', 'plazas' => 90],
            ['localizacion' => 'Refugi de Ventosa i Calvell', 'nombre' => 'Refugi Ventosa i Calvell', 'tipo' => 'albergue', 'telefono' => '+34 973 624 216', 'plazas' => 50],
            ['localizacion' => 'Refugi de la Conca', 'nombre' => 'Refugi de la Conca', 'tipo' => 'albergue', 'telefono' => '+34 973 624 217', 'plazas' => 60],
            ['localizacion' => 'Refugi de Josep M. Blanc', 'nombre' => 'Refugi Josep M. Blanc', 'tipo' => 'albergue', 'telefono' => '+34 973 624 218', 'plazas' => 70],
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

        $this->command->info('Carros de Foc creado con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
