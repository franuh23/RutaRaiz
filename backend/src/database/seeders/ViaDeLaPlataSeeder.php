<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class ViaDeLaPlataSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Vía de la Plata · Camino Mozárabe'],
            [
                'dificultad' => 'alta',
                'inicio' => 'Sevilla',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 1000,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Mapa_V%C3%ADa_de_la_Plata.svg/800px-Mapa_V%C3%ADa_de_la_Plata.svg.png',
                'activo' => true
            ]
        );

        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            // ANDALUCÍA (Sevilla a Huelva/Extremadura)
            ['nombre' => 'Sevilla', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 1000, 'descripcion' => 'Capital andaluza. Inicio de la Vía de la Plata. Giralda y Archivo de Indias.'],
            ['nombre' => 'Santiponce', 'distancia_desde_inicio' => 9.0, 'distancia_desde_fin' => 991, 'descripcion' => 'Itálica, ciudad romana natal de Trajano y Adriano.'],
            ['nombre' => 'Castilblanco de los Arroyos', 'distancia_desde_inicio' => 32.5, 'distancia_desde_fin' => 967.5],
            ['nombre' => 'Almadén de la Plata', 'distancia_desde_inicio' => 53.0, 'distancia_desde_fin' => 947],
            ['nombre' => 'El Real de la Jara', 'distancia_desde_inicio' => 75.0, 'distancia_desde_fin' => 925],
            ['nombre' => 'Monesterio', 'distancia_desde_inicio' => 95.0, 'distancia_desde_fin' => 905],
            ['nombre' => 'Fuente de Cantos', 'distancia_desde_inicio' => 119.0, 'distancia_desde_fin' => 881],
            ['nombre' => 'Zafra', 'distancia_desde_inicio' => 141.0, 'distancia_desde_fin' => 859, 'descripcion' => 'Ciudad medieval conocida como "Pequeña Sevilla".'],
            ['nombre' => 'Villafranca de los Barros', 'distancia_desde_inicio' => 160.0, 'distancia_desde_fin' => 840],
            ['nombre' => 'Torremejía', 'distancia_desde_inicio' => 184.0, 'distancia_desde_fin' => 816],

            // EXTREMADURA
            ['nombre' => 'Mérida', 'distancia_desde_inicio' => 200.0, 'distancia_desde_fin' => 800, 'descripcion' => 'Capital extremeña. Conjunto arqueológico romano Patrimonio de la Humanidad.'],
            ['nombre' => 'Aljucén', 'distancia_desde_inicio' => 219.0, 'distancia_desde_fin' => 781],
            ['nombre' => 'Alcuéscar', 'distancia_desde_inicio' => 241.0, 'distancia_desde_fin' => 759],
            ['nombre' => 'Cáceres', 'distancia_desde_inicio' => 270.0, 'distancia_desde_fin' => 730, 'descripcion' => 'Ciudad monumental, Patrimonio de la Humanidad.'],
            ['nombre' => 'Casar de Cáceres', 'distancia_desde_inicio' => 300.0, 'distancia_desde_fin' => 700],
            ['nombre' => 'Cañaveral', 'distancia_desde_inicio' => 327.0, 'distancia_desde_fin' => 673],
            ['nombre' => 'Galisteo', 'distancia_desde_inicio' => 345.0, 'distancia_desde_fin' => 655],
            ['nombre' => 'Carcaboso', 'distancia_desde_inicio' => 365.0, 'distancia_desde_fin' => 635],
            ['nombre' => 'Oliva de Plasencia', 'distancia_desde_inicio' => 380.0, 'distancia_desde_fin' => 620],

            // CASTILLA Y LEÓN (Salamanca)
            ['nombre' => 'Baños de Montemayor', 'distancia_desde_inicio' => 400.0, 'distancia_desde_fin' => 600],
            ['nombre' => 'La Calzada de Béjar', 'distancia_desde_inicio' => 420.0, 'distancia_desde_fin' => 580],
            ['nombre' => 'Salamanca', 'distancia_desde_inicio' => 460.0, 'distancia_desde_fin' => 540, 'descripcion' => 'Ciudad universitaria, Plaza Mayor y Universidad, Patrimonio de la Humanidad.'],
            ['nombre' => 'El Cubo de Tierra del Vino', 'distancia_desde_inicio' => 500.0, 'distancia_desde_fin' => 500],
            ['nombre' => 'Zamora', 'distancia_desde_inicio' => 530.0, 'distancia_desde_fin' => 470, 'descripcion' => 'Ciudad del románico. 24 iglesias románicas.'],

            // CASTILLA Y LEÓN (Zamora)
            ['nombre' => 'Granja de Moreruela', 'distancia_desde_inicio' => 560.0, 'distancia_desde_fin' => 440],
            ['nombre' => 'Tábara', 'distancia_desde_inicio' => 585.0, 'distancia_desde_fin' => 415],
            ['nombre' => 'Santa Marta de Tera', 'distancia_desde_inicio' => 610.0, 'distancia_desde_fin' => 390],
            ['nombre' => 'Rionegro del Puente', 'distancia_desde_inicio' => 635.0, 'distancia_desde_fin' => 365],
            ['nombre' => 'Mombuey', 'distancia_desde_inicio' => 655.0, 'distancia_desde_fin' => 345],
            ['nombre' => 'Puebla de Sanabria', 'distancia_desde_inicio' => 685.0, 'distancia_desde_fin' => 315, 'descripcion' => 'Villa medieval con castillo, puerta de entrada a Sanabria.'],

            // CASTILLA Y LEÓN (León - Ourense)
            ['nombre' => 'A Gudiña (Ourense)', 'distancia_desde_inicio' => 720.0, 'distancia_desde_fin' => 280],
            ['nombre' => 'Laza', 'distancia_desde_inicio' => 750.0, 'distancia_desde_fin' => 250],
            ['nombre' => 'Verín', 'distancia_desde_inicio' => 770.0, 'distancia_desde_fin' => 230],
            ['nombre' => 'Monterrei', 'distancia_desde_inicio' => 785.0, 'distancia_desde_fin' => 215],

            // GALICIA (Ourense)
            ['nombre' => 'Ourense', 'distancia_desde_inicio' => 820.0, 'distancia_desde_fin' => 180, 'descripcion' => 'Capital termal gallega. Puente romano y burgas termales.'],
            ['nombre' => 'Cea', 'distancia_desde_inicio' => 850.0, 'distancia_desde_fin' => 150],
            ['nombre' => 'Lalín', 'distancia_desde_inicio' => 880.0, 'distancia_desde_fin' => 120],
            ['nombre' => 'Silleda', 'distancia_desde_inicio' => 910.0, 'distancia_desde_fin' => 90],
            ['nombre' => 'Bandeira', 'distancia_desde_inicio' => 930.0, 'distancia_desde_fin' => 70],
            ['nombre' => 'Ponte Ulla', 'distancia_desde_inicio' => 955.0, 'distancia_desde_fin' => 45],
            ['nombre' => 'Monte do Gozo', 'distancia_desde_inicio' => 990.0, 'distancia_desde_fin' => 10],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 1000.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del Camino. Catedral del Apóstol Santiago.'],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Sevilla
            ['localizacion' => 'Sevilla', 'nombre' => 'Albergue Triana Backpackers', 'tipo' => 'albergue', 'telefono' => '+34 954 45 00 00'],
            ['localizacion' => 'Sevilla', 'nombre' => 'Hotel Simón', 'tipo' => 'hotel', 'telefono' => '+34 954 22 66 60'],

            // Santiponce
            ['localizacion' => 'Santiponce', 'nombre' => 'Hotel Anfiteatro Romano', 'tipo' => 'hotel', 'telefono' => '+34 954 39 40 00'],

            // Zafra
            ['localizacion' => 'Zafra', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 924 55 00 00'],
            ['localizacion' => 'Zafra', 'nombre' => 'Hotel Cervantes', 'tipo' => 'hotel', 'telefono' => '+34 924 55 20 00'],

            // Mérida
            ['localizacion' => 'Mérida', 'nombre' => 'Albergue Turístico', 'tipo' => 'albergue', 'telefono' => '+34 924 31 50 00'],
            ['localizacion' => 'Mérida', 'nombre' => 'Hotel Ilunion Las Lomas', 'tipo' => 'hotel', 'telefono' => '+34 924 31 55 00'],

            // Cáceres
            ['localizacion' => 'Cáceres', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 927 24 50 00'],
            ['localizacion' => 'Cáceres', 'nombre' => 'Hotel Casa Don Fernando', 'tipo' => 'hotel', 'telefono' => '+34 927 21 00 00'],

            // Salamanca
            ['localizacion' => 'Salamanca', 'nombre' => 'Albergue Salamanca', 'tipo' => 'albergue', 'telefono' => '+34 923 26 00 00'],
            ['localizacion' => 'Salamanca', 'nombre' => 'Hotel Rector', 'tipo' => 'hotel', 'telefono' => '+34 923 21 48 00'],

            // Zamora
            ['localizacion' => 'Zamora', 'nombre' => 'Albergue de Peregrinos', 'tipo' => 'albergue', 'telefono' => '+34 980 53 00 00'],
            ['localizacion' => 'Zamora', 'nombre' => 'Hotel Doña Urraca', 'tipo' => 'hotel', 'telefono' => '+34 980 51 81 00'],

            // Puebla de Sanabria
            ['localizacion' => 'Puebla de Sanabria', 'nombre' => 'Albergue El Palacio', 'tipo' => 'albergue', 'telefono' => '+34 980 62 00 00'],

            // Ourense
            ['localizacion' => 'Ourense', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 988 23 00 00'],
            ['localizacion' => 'Ourense', 'nombre' => 'Hotel Santo Estevo', 'tipo' => 'hotel', 'telefono' => '+34 988 24 00 00'],

            // Santiago
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Albergue Seminario Menor', 'tipo' => 'albergue', 'telefono' => '+34 981 56 38 10', 'plazas' => 300],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Parador Reyes Católicos', 'tipo' => 'hotel', 'telefono' => '+34 981 58 22 00'],
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

        $this->command->info('Vía de la Plata creada con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
