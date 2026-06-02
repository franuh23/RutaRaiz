<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CaminoDelCidSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino del Cid (GR-160) · Tramo Aragón'],
            [
                'dificultad' => 'media',
                'inicio' => 'Santa María de Huerta',
                'fin' => 'Olba',
                'kilometros' => 345,
                'imagen' => 'https://www.caminodelcid.org/img/logo-cdc.png',
                'activo' => true
            ]
        );

        // Limpiar datos antiguos
        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            // Etapa 25: Santa María de Huerta - Ariza
            ['nombre' => 'Santa María de Huerta', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 345, 'descripcion' => 'Monasterio cisterciense del siglo XII.'],
            ['nombre' => 'Ariza', 'distancia_desde_inicio' => 18.4, 'distancia_desde_fin' => 326.6],

            // Etapa 26: Ariza - Alhama de Aragón
            ['nombre' => 'Alhama de Aragón', 'distancia_desde_inicio' => 33.3, 'distancia_desde_fin' => 311.7, 'descripcion' => 'Famoso por sus balnearios de aguas termales.'],

            // Etapa 27: Alhama de Aragón - Ateca
            ['nombre' => 'Ateca', 'distancia_desde_inicio' => 49.1, 'distancia_desde_fin' => 295.9],

            // Etapa 28: Ateca - Munébrega
            ['nombre' => 'Munébrega', 'distancia_desde_inicio' => 65.9, 'distancia_desde_fin' => 279.1],

            // Etapa 29: Munébrega - Acered
            ['nombre' => 'Acered', 'distancia_desde_inicio' => 86.5, 'distancia_desde_fin' => 258.5],

            // Etapa 30: Acered - Daroca
            ['nombre' => 'Daroca', 'distancia_desde_inicio' => 108.9, 'distancia_desde_fin' => 236.1, 'descripcion' => 'Ciudad medieval con impresionantes murallas.'],

            // Etapa 31: Daroca - Calamocha
            ['nombre' => 'Calamocha', 'distancia_desde_inicio' => 138.7, 'distancia_desde_fin' => 206.3],

            // Etapa 32: Calamocha - Monreal del Campo
            ['nombre' => 'Monreal del Campo', 'distancia_desde_inicio' => 157.9, 'distancia_desde_fin' => 187.1],

            // Etapa 33: Monreal del Campo - Pobo de Dueñas
            ['nombre' => 'Pobo de Dueñas', 'distancia_desde_inicio' => 175.5, 'distancia_desde_fin' => 169.5],

            // Etapa 38: Orihuela del Tremedal - Bronchales
            ['nombre' => 'Orihuela del Tremedal', 'distancia_desde_inicio' => 187.9, 'distancia_desde_fin' => 157.1],
            ['nombre' => 'Bronchales', 'distancia_desde_inicio' => 200.3, 'distancia_desde_fin' => 144.7],

            // Etapa 39: Bronchales - Albarracín
            ['nombre' => 'Albarracín', 'distancia_desde_inicio' => 225.4, 'distancia_desde_fin' => 119.6, 'descripcion' => 'Uno de los pueblos más bonitos de España.'],

            // Etapa 40: Albarracín - Cella
            ['nombre' => 'Cella', 'distancia_desde_inicio' => 246.2, 'distancia_desde_fin' => 98.8],

            // Etapa 41: Cella - Teruel
            ['nombre' => 'Teruel', 'distancia_desde_inicio' => 269.2, 'distancia_desde_fin' => 75.8, 'descripcion' => 'Capital del mudéjar, famosa por sus torres y los Amantes.'],

            // Etapa 42: Teruel - La Puebla de Valverde
            ['nombre' => 'La Puebla de Valverde', 'distancia_desde_inicio' => 298.4, 'distancia_desde_fin' => 46.6],

            // Etapa 43: La Puebla de Valverde - Mora de Rubielos
            ['nombre' => 'Mora de Rubielos', 'distancia_desde_inicio' => 323.8, 'distancia_desde_fin' => 21.2, 'descripcion' => 'Castillo gótico renacentista.'],

            // Etapa 44: Mora de Rubielos - Rubielos de Mora
            ['nombre' => 'Rubielos de Mora', 'distancia_desde_inicio' => 340.6, 'distancia_desde_fin' => 4.4],

            // Etapa 45: Rubielos de Mora - Olba
            ['nombre' => 'Olba', 'distancia_desde_inicio' => 345.0, 'distancia_desde_fin' => 0],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        // Obtener IDs de localizaciones para asignar alojamientos
        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Santa María de Huerta
            ['localizacion' => 'Santa María de Huerta', 'nombre' => 'Monasterio de Santa María', 'tipo' => 'albergue', 'telefono' => '+34 975 327 001'],

            // Ariza
            ['localizacion' => 'Ariza', 'nombre' => 'Albergue Quinta del Jalón', 'tipo' => 'albergue', 'telefono' => '+34 635 647 666'],

            // Alhama de Aragón
            ['localizacion' => 'Alhama de Aragón', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 976 844 108'],
            ['localizacion' => 'Alhama de Aragón', 'nombre' => 'Hotel Termas Pallarés', 'tipo' => 'hotel', 'telefono' => '+34 976 849 115'],

            // Ateca
            ['localizacion' => 'Ateca', 'nombre' => 'Hotel Ateca', 'tipo' => 'hotel', 'telefono' => '+34 976 843 021'],

            // Munébrega
            ['localizacion' => 'Munébrega', 'nombre' => 'Albergue La Zarandilla', 'tipo' => 'albergue', 'telefono' => '+34 606 733 791', 'plazas' => 72],

            // Daroca
            ['localizacion' => 'Daroca', 'nombre' => 'Albergue Juvenil Daroca', 'tipo' => 'albergue', 'telefono' => '+34 976 800 129', 'plazas' => 62],

            // Calamocha
            ['localizacion' => 'Calamocha', 'nombre' => 'Hotel Jiloca', 'tipo' => 'hotel', 'telefono' => '+34 978 730 800'],

            // Monreal del Campo
            ['localizacion' => 'Monreal del Campo', 'nombre' => 'Hostal Plaza', 'tipo' => 'hostal', 'telefono' => '+34 978 789 144'],

            // Orihuela del Tremedal
            ['localizacion' => 'Orihuela del Tremedal', 'nombre' => 'Albergue Casa del Pueblo', 'tipo' => 'albergue', 'telefono' => '+34 978 708 549'],

            // Bronchales
            ['localizacion' => 'Bronchales', 'nombre' => 'Hotel Bronchales', 'tipo' => 'hotel', 'telefono' => '+34 978 708 033'],

            // Albarracín
            ['localizacion' => 'Albarracín', 'nombre' => 'Albergue Rosa Brios', 'tipo' => 'albergue', 'telefono' => '+34 978 710 266', 'plazas' => 74],
            ['localizacion' => 'Albarracín', 'nombre' => 'Albergue Báguena', 'tipo' => 'albergue', 'telefono' => '+34 978 733 107', 'plazas' => 60],

            // Cella
            ['localizacion' => 'Cella', 'nombre' => 'Hotel Cella', 'tipo' => 'hotel', 'telefono' => '+34 978 780 400'],

            // Teruel
            ['localizacion' => 'Teruel', 'nombre' => 'Albergue Juvenil Luis Buñuel', 'tipo' => 'albergue', 'telefono' => '+34 978 602 223', 'plazas' => 160],
            ['localizacion' => 'Teruel', 'nombre' => 'Hotel Mudéjar', 'tipo' => 'hotel', 'telefono' => '+34 978 605 151'],
            ['localizacion' => 'Teruel', 'nombre' => 'Hostal Oriente', 'tipo' => 'hostal', 'telefono' => '+34 978 600 850'],

            // La Puebla de Valverde
            ['localizacion' => 'La Puebla de Valverde', 'nombre' => 'Hotel La Puebla', 'tipo' => 'hotel', 'telefono' => '+34 978 789 000'],

            // Mora de Rubielos
            ['localizacion' => 'Mora de Rubielos', 'nombre' => 'Hotel Jaime I', 'tipo' => 'hotel', 'telefono' => '+34 978 800 075'],

            // Rubielos de Mora
            ['localizacion' => 'Rubielos de Mora', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 978 800 448'],

            // Olba
            ['localizacion' => 'Olba', 'nombre' => 'Albergue Molino de Olba', 'tipo' => 'albergue', 'telefono' => '+34 978 031 563', 'plazas' => 12],
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

        $this->command->info('Camino del Cid (GR-160) creado con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
