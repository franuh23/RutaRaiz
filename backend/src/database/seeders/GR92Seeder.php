<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class GR92Seeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'GR-92 · Camino de Ronda (Sendero del Mediterráneo)'],
            [
                'dificultad' => 'media',
                'inicio' => 'Portbou',
                'fin' => 'Marbella',
                'kilometros' => 583,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/GR-92_-_SPAR_-_Catalonia.png/800px-GR-92_-_SPAR_-_Catalonia.png',
                'activo' => true
            ]
        );

        // Limpiar datos antiguos
        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            // CATALUÑA (Costa Brava)
            ['nombre' => 'Portbou', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 583, 'descripcion' => 'Pueblo fronterizo con Francia. Inicio del GR-92.'],
            ['nombre' => 'Llançà', 'distancia_desde_inicio' => 10.0, 'distancia_desde_fin' => 573],
            ['nombre' => 'Cadaqués', 'distancia_desde_inicio' => 29.5, 'distancia_desde_fin' => 553.5, 'descripcion' => 'Pueblo blanco, Casa-Museo de Dalí.'],
            ['nombre' => 'Roses', 'distancia_desde_inicio' => 51.0, 'distancia_desde_fin' => 532, 'descripcion' => 'Ciudad con la Ciudadela y playas.'],
            ['nombre' => 'Empuriabrava', 'distancia_desde_inicio' => 70.0, 'distancia_desde_fin' => 513],
            ['nombre' => 'L\'Escala', 'distancia_desde_inicio' => 90.5, 'distancia_desde_fin' => 492.5, 'descripcion' => 'Famosa por sus anchoas y ruinas de Ampurias.'],
            ['nombre' => 'Torroella de Montgrí', 'distancia_desde_inicio' => 111.5, 'distancia_desde_fin' => 471.5],
            ['nombre' => 'Begur', 'distancia_desde_inicio' => 133.0, 'distancia_desde_fin' => 450, 'descripcion' => 'Pueblo medieval con castillo.'],
            ['nombre' => 'Palamós', 'distancia_desde_inicio' => 155.5, 'distancia_desde_fin' => 427.5],
            ['nombre' => 'Sant Feliu de Guíxols', 'distancia_desde_inicio' => 170.0, 'distancia_desde_fin' => 413],
            ['nombre' => 'Tossa de Mar', 'distancia_desde_inicio' => 193.5, 'distancia_desde_fin' => 389.5, 'descripcion' => 'Villa fortificada medieval.'],
            ['nombre' => 'Lloret de Mar', 'distancia_desde_inicio' => 206.5, 'distancia_desde_fin' => 376.5],
            ['nombre' => 'Blanes', 'distancia_desde_inicio' => 220.0, 'distancia_desde_fin' => 363],
            ['nombre' => 'Malgrat de Mar', 'distancia_desde_inicio' => 235.0, 'distancia_desde_fin' => 348],

            // COMUNIDAD VALENCIANA
            ['nombre' => 'Alcanar', 'distancia_desde_inicio' => 260.0, 'distancia_desde_fin' => 323],
            ['nombre' => 'Sant Carles de la Ràpita', 'distancia_desde_inicio' => 280.0, 'distancia_desde_fin' => 303],
            ['nombre' => 'Amposta', 'distancia_desde_inicio' => 310.0, 'distancia_desde_fin' => 273],
            ['nombre' => 'L\'Ampolla', 'distancia_desde_inicio' => 340.0, 'distancia_desde_fin' => 243],
            ['nombre' => 'L\'Ametlla de Mar', 'distancia_desde_inicio' => 365.0, 'distancia_desde_fin' => 218],
            ['nombre' => 'L\'Hospitalet de l\'Infant', 'distancia_desde_inicio' => 390.0, 'distancia_desde_fin' => 193],
            ['nombre' => 'Cambrils', 'distancia_desde_inicio' => 420.0, 'distancia_desde_fin' => 163],
            ['nombre' => 'Salou', 'distancia_desde_inicio' => 435.0, 'distancia_desde_fin' => 148],
            ['nombre' => 'Tarragona', 'distancia_desde_inicio' => 460.0, 'distancia_desde_fin' => 123, 'descripcion' => 'Patrimonio de la Humanidad. Anfiteatro romano.'],
            ['nombre' => 'Cambrils', 'distancia_desde_inicio' => 420.0, 'distancia_desde_fin' => 163],
            ['nombre' => 'Guardamar del Segura', 'distancia_desde_inicio' => 500.0, 'distancia_desde_fin' => 83],
            ['nombre' => 'Torrevieja', 'distancia_desde_inicio' => 520.0, 'distancia_desde_fin' => 63],
            ['nombre' => 'Pilar de la Horadada', 'distancia_desde_inicio' => 550.0, 'distancia_desde_fin' => 33],

            // ANDALUCÍA
            ['nombre' => 'Marbella', 'distancia_desde_inicio' => 583.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del GR-92. Ciudad turística de la Costa del Sol.'],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        // Obtener IDs de localizaciones para asignar alojamientos
        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Portbou
            ['localizacion' => 'Portbou', 'nombre' => 'Hostal Porto', 'tipo' => 'hostal', 'telefono' => '+34 972 39 10 00'],
            ['localizacion' => 'Portbou', 'nombre' => 'Albergue Portbou', 'tipo' => 'albergue', 'telefono' => '+34 972 39 10 00'],

            // Llançà
            ['localizacion' => 'Llançà', 'nombre' => 'Hotel Grifeu', 'tipo' => 'hotel', 'telefono' => '+34 972 38 01 20'],
            ['localizacion' => 'Llançà', 'nombre' => 'Hostal La Goleta', 'tipo' => 'hostal', 'telefono' => '+34 972 38 00 00'],

            // Cadaqués
            ['localizacion' => 'Cadaqués', 'nombre' => 'Hotel Playa Sol', 'tipo' => 'hotel', 'telefono' => '+34 972 25 81 00'],
            ['localizacion' => 'Cadaqués', 'nombre' => 'Hostal Marina', 'tipo' => 'hostal', 'telefono' => '+34 972 25 81 50'],

            // Roses
            ['localizacion' => 'Roses', 'nombre' => 'Hotel Carmen', 'tipo' => 'hotel', 'telefono' => '+34 972 25 71 00'],
            ['localizacion' => 'Roses', 'nombre' => 'Albergue Juventud Roses', 'tipo' => 'albergue', 'telefono' => '+34 972 25 72 00'],

            // Empuriabrava
            ['localizacion' => 'Empuriabrava', 'nombre' => 'Hotel Empuriabrava', 'tipo' => 'hotel', 'telefono' => '+34 972 45 02 12'],
            ['localizacion' => 'Empuriabrava', 'nombre' => 'Camping Almata', 'tipo' => 'camping', 'telefono' => '+34 972 45 50 20'],

            // L'Escala
            ['localizacion' => 'L\'Escala', 'nombre' => 'Hotel L\'Escala', 'tipo' => 'hotel', 'telefono' => '+34 972 77 00 34'],
            ['localizacion' => 'L\'Escala', 'nombre' => 'Apartaments El Molí', 'tipo' => 'hostal', 'telefono' => '+34 972 77 00 00'],

            // Torroella de Montgrí
            ['localizacion' => 'Torroella de Montgrí', 'nombre' => 'Hotel Hiking GR92', 'tipo' => 'hotel', 'telefono' => '+34 972 60 00 00'],

            // Begur
            ['localizacion' => 'Begur', 'nombre' => 'Hotel Eetu', 'tipo' => 'hotel', 'telefono' => '+34 972 62 40 00'],
            ['localizacion' => 'Begur', 'nombre' => 'Hostal Galena', 'tipo' => 'hostal', 'telefono' => '+34 972 62 40 00'],

            // Palamós
            ['localizacion' => 'Palamós', 'nombre' => 'Hotel Trias', 'tipo' => 'hotel', 'telefono' => '+34 972 60 00 00'],
            ['localizacion' => 'Palamós', 'nombre' => 'Hostal La Fosca', 'tipo' => 'hostal', 'telefono' => '+34 972 60 00 00'],

            // Sant Feliu de Guíxols
            ['localizacion' => 'Sant Feliu de Guíxols', 'nombre' => 'Hotel Julimar', 'tipo' => 'hotel', 'telefono' => '+34 972 82 00 00'],

            // Tossa de Mar
            ['localizacion' => 'Tossa de Mar', 'nombre' => 'Hotel Delfín', 'tipo' => 'hotel', 'telefono' => '+34 972 34 01 50'],
            ['localizacion' => 'Tossa de Mar', 'nombre' => 'Hostal Victoria', 'tipo' => 'hostal', 'telefono' => '+34 972 34 00 00'],

            // Lloret de Mar
            ['localizacion' => 'Lloret de Mar', 'nombre' => 'Hotel Gran Garbí', 'tipo' => 'hotel', 'telefono' => '+34 972 36 49 25'],

            // Blanes
            ['localizacion' => 'Blanes', 'nombre' => 'Hotel Horitzó', 'tipo' => 'hotel', 'telefono' => '+34 972 35 00 00'],

            // Tarragona
            ['localizacion' => 'Tarragona', 'nombre' => 'Hotel Plaça de la Font', 'tipo' => 'hotel', 'telefono' => '+34 977 24 00 00'],
            ['localizacion' => 'Tarragona', 'nombre' => 'Hostal Noria', 'tipo' => 'hostal', 'telefono' => '+34 977 23 00 00'],

            // Guardamar del Segura
            ['localizacion' => 'Guardamar del Segura', 'nombre' => 'Hotel Guardamar', 'tipo' => 'hotel', 'telefono' => '+34 966 72 00 00'],

            // Torrevieja
            ['localizacion' => 'Torrevieja', 'nombre' => 'Hotel Fontana', 'tipo' => 'hotel', 'telefono' => '+34 965 70 00 00'],

            // Pilar de la Horadada
            ['localizacion' => 'Pilar de la Horadada', 'nombre' => 'Hotel Río', 'tipo' => 'hotel', 'telefono' => '+34 965 70 00 00'],

            // Marbella
            ['localizacion' => 'Marbella', 'nombre' => 'Hotel Don Pepe', 'tipo' => 'hotel', 'telefono' => '+34 952 77 00 00'],
            ['localizacion' => 'Marbella', 'nombre' => 'Albergue Marbella', 'tipo' => 'albergue', 'telefono' => '+34 952 77 00 00'],
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

        $this->command->info('GR-92 · Camino de Ronda creado con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
