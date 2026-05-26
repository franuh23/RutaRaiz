<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;

class GR223Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la ruta (circular, mismo inicio y fin)
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'GR-223 · Camí de Cavalls'],
            [
                'dificultad' => 'media',
                'inicio' => 'Mahón',
                'fin' => 'Mahón',
                'kilometros' => 185,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7d/Camí_de_Cavalls_logo.svg/800px-Camí_de_Cavalls_logo.svg.png',
                'activo' => true
            ]
        );

        // Eliminar localizaciones antiguas para evitar duplicados
        $ruta->localizaciones()->delete();

        // Insertar localizaciones (distancia_desde_inicio acumulada)
        $localizaciones = [
            // ========== TRAMO 1: Mahón a Ciutadella (costa sur) ==========
            ['nombre' => 'Mahón', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 185, 'descripcion' => 'Capital de Menorca. Puerto natural más grande del Mediterráneo.'],
            ['nombre' => 'Port de Maó', 'distancia_desde_inicio' => 2.5, 'distancia_desde_fin' => 182.5],
            ['nombre' => 'Cala Figuera', 'distancia_desde_inicio' => 5.0, 'distancia_desde_fin' => 180.0],
            ['nombre' => 'Punta des Verger', 'distancia_desde_inicio' => 8.0, 'distancia_desde_fin' => 177.0],
            ['nombre' => 'Es Murtar', 'distancia_desde_inicio' => 10.5, 'distancia_desde_fin' => 174.5],
            ['nombre' => 'Cala en Porter', 'distancia_desde_inicio' => 12.5, 'distancia_desde_fin' => 172.5, 'descripcion' => 'Cueva de sa Cova d\'en Xoroi.'],
            ['nombre' => 'Cova des Coloms', 'distancia_desde_inicio' => 15.0, 'distancia_desde_fin' => 170.0],
            ['nombre' => 'Torre d\'en Galmés', 'distancia_desde_inicio' => 17.5, 'distancia_desde_fin' => 167.5, 'descripcion' => 'Poblado talaiótico más grande de Menorca.'],
            ['nombre' => 'Cala de ses Roques', 'distancia_desde_inicio' => 19.0, 'distancia_desde_fin' => 166.0],
            ['nombre' => 'Son Bou', 'distancia_desde_inicio' => 20.7, 'distancia_desde_fin' => 164.3, 'descripcion' => 'Playa más larga de Menorca.'],
            ['nombre' => 'Cala Llucalari', 'distancia_desde_inicio' => 23.0, 'distancia_desde_fin' => 162.0],
            ['nombre' => 'Cala Trebalúger', 'distancia_desde_inicio' => 25.5, 'distancia_desde_fin' => 159.5],
            ['nombre' => 'Sant Tomàs', 'distancia_desde_inicio' => 28.0, 'distancia_desde_fin' => 157.0],
            ['nombre' => 'Cala en Bosc', 'distancia_desde_inicio' => 30.0, 'distancia_desde_fin' => 155.0],
            ['nombre' => 'Cala Galdana', 'distancia_desde_inicio' => 32.0, 'distancia_desde_fin' => 153.0, 'descripcion' => 'Playa virgen rodeada de acantilados.'],
            ['nombre' => 'Cala Mitjana', 'distancia_desde_inicio' => 34.5, 'distancia_desde_fin' => 150.5],
            ['nombre' => 'Cala Macarella', 'distancia_desde_inicio' => 37.0, 'distancia_desde_fin' => 148.0],
            ['nombre' => 'Cala Turqueta', 'distancia_desde_inicio' => 39.0, 'distancia_desde_fin' => 146.0],
            ['nombre' => 'Son Saura', 'distancia_desde_inicio' => 41.0, 'distancia_desde_fin' => 144.0],
            ['nombre' => 'Cala en Brut', 'distancia_desde_inicio' => 43.5, 'distancia_desde_fin' => 141.5],
            ['nombre' => 'Ciutadella', 'distancia_desde_inicio' => 47.5, 'distancia_desde_fin' => 137.5, 'descripcion' => 'Antigua capital de Menorca. Casco histórico impresionante.'],

            // ========== TRAMO 2: Ciutadella a Fornells (costa norte) ==========
            ['nombre' => 'Cala en Blanes', 'distancia_desde_inicio' => 51.0, 'distancia_desde_fin' => 134.0],
            ['nombre' => 'Cala Santandria', 'distancia_desde_inicio' => 53.5, 'distancia_desde_fin' => 131.5],
            ['nombre' => 'Cala Morell', 'distancia_desde_inicio' => 56.5, 'distancia_desde_fin' => 128.5],
            ['nombre' => 'Punta Nati', 'distancia_desde_inicio' => 60.5, 'distancia_desde_fin' => 124.5, 'descripcion' => 'Faro y paisaje lunar.'],
            ['nombre' => 'Cala del Pilar', 'distancia_desde_inicio' => 65.5, 'distancia_desde_fin' => 119.5],
            ['nombre' => 'Cala Viola', 'distancia_desde_inicio' => 69.5, 'distancia_desde_fin' => 115.5],
            ['nombre' => 'Els Alocs', 'distancia_desde_inicio' => 73.0, 'distancia_desde_fin' => 112.0],
            ['nombre' => 'Cala Fontanelles', 'distancia_desde_inicio' => 77.0, 'distancia_desde_fin' => 108.0],
            ['nombre' => 'Cala en Calderer', 'distancia_desde_inicio' => 81.5, 'distancia_desde_fin' => 103.5],
            ['nombre' => 'Cala Pregonda', 'distancia_desde_inicio' => 85.0, 'distancia_desde_fin' => 100.0],
            ['nombre' => 'Cala Cavalleria', 'distancia_desde_inicio' => 89.0, 'distancia_desde_fin' => 96.0, 'descripcion' => 'Faro y playa de arena dorada.'],
            ['nombre' => 'Fornells', 'distancia_desde_inicio' => 94.0, 'distancia_desde_fin' => 91.0, 'descripcion' => 'Pueblo pesquero famoso por su langosta.'],

            // ========== TRAMO 3: Fornells a Mahón (costa norte y este) ==========
            ['nombre' => 'Cala Tirant', 'distancia_desde_inicio' => 98.5, 'distancia_desde_fin' => 86.5],
            ['nombre' => 'Es Mercadal', 'distancia_desde_inicio' => 103.0, 'distancia_desde_fin' => 82.0],
            ['nombre' => 'Monte Toro', 'distancia_desde_inicio' => 108.0, 'distancia_desde_fin' => 77.0, 'descripcion' => 'Punto más alto de Menorca (358m). Santuario.'],
            ['nombre' => 'Cala Morts', 'distancia_desde_inicio' => 113.0, 'distancia_desde_fin' => 72.0],
            ['nombre' => 'Cala en Tuset', 'distancia_desde_inicio' => 117.0, 'distancia_desde_fin' => 68.0],
            ['nombre' => 'Es Grau', 'distancia_desde_inicio' => 122.0, 'distancia_desde_fin' => 63.0, 'descripcion' => 'Parque Natural de s\'Albufera des Grau.'],
            ['nombre' => 'Sa Mesquida', 'distancia_desde_inicio' => 128.0, 'distancia_desde_fin' => 57.0],
            ['nombre' => 'Cala San Esteve', 'distancia_desde_inicio' => 133.0, 'distancia_desde_fin' => 52.0],
            ['nombre' => 'Cala Teulera', 'distancia_desde_inicio' => 138.0, 'distancia_desde_fin' => 47.0],
            ['nombre' => 'Es Canutells', 'distancia_desde_inicio' => 143.0, 'distancia_desde_fin' => 42.0],
            ['nombre' => 'Cala en Porter (retorno)', 'distancia_desde_inicio' => 148.0, 'distancia_desde_fin' => 37.0],
            ['nombre' => 'Binibeca', 'distancia_desde_inicio' => 153.0, 'distancia_desde_fin' => 32.0, 'descripcion' => 'Pueblo blanco típico de pescadores.'],
            ['nombre' => 'Punta Prima', 'distancia_desde_inicio' => 158.5, 'distancia_desde_fin' => 26.5],
            ['nombre' => 'Cala Alcaufar', 'distancia_desde_inicio' => 163.5, 'distancia_desde_fin' => 21.5],
            ['nombre' => 'Ses Salines', 'distancia_desde_inicio' => 168.5, 'distancia_desde_fin' => 16.5],
            ['nombre' => 'Es Castell', 'distancia_desde_inicio' => 174.0, 'distancia_desde_fin' => 11.0],
            ['nombre' => 'Cala de Sant Esteve', 'distancia_desde_inicio' => 178.5, 'distancia_desde_fin' => 6.5],
            ['nombre' => 'Mahón (retorno)', 'distancia_desde_inicio' => 185.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del recorrido circular del Camí de Cavalls.']
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }
    }
}
