<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;

class CaminoPrimitivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la ruta
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino Primitivo'],
            [
                'dificultad' => 'media',
                'inicio' => 'Oviedo',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 310.5,
                'imagen' => 'https://www.caminoguidebook.com/wp-content/uploads/2019/08/CPR-Overall-Map.png',
                'activo' => true
            ]
        );

        // Eliminar localizaciones antiguas para evitar duplicados
        $ruta->localizaciones()->delete();

        // Insertar localizaciones
        $localizaciones = [
            ['nombre' => 'Oviedo', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 320, 'descripcion' => 'Inicio del camino Primitivo.'],
            ['nombre' => 'Escamplero', 'distancia_desde_inicio' => 11.7, 'distancia_desde_fin' => 308.3],
            ['nombre' => 'Paladín', 'distancia_desde_inicio' => 19.0, 'distancia_desde_fin' => 301.0],
            ['nombre' => 'Grado', 'distancia_desde_inicio' => 25.2, 'distancia_desde_fin' => 294.8],
            ['nombre' => 'San Juan de Villapañada', 'distancia_desde_inicio' => 28.8, 'distancia_desde_fin' => 291.2],
            ['nombre' => 'Doriga', 'distancia_desde_inicio' => 33.0, 'distancia_desde_fin' => 287.0],
            ['nombre' => 'Cornellana', 'distancia_desde_inicio' => 35.9, 'distancia_desde_fin' => 284.1, 'descripcion' => 'Monasterio de San Salvador.'],
            ['nombre' => 'Casazorrina', 'distancia_desde_inicio' => 44.4, 'distancia_desde_fin' => 275.6],
            ['nombre' => 'Salas', 'distancia_desde_inicio' => 47.5, 'distancia_desde_fin' => 272.5],
            ['nombre' => 'Bodenaya', 'distancia_desde_inicio' => 54.6, 'distancia_desde_fin' => 265.4],
            ['nombre' => 'La Espina', 'distancia_desde_inicio' => 55.7, 'distancia_desde_fin' => 264.3],
            ['nombre' => 'Tineo', 'distancia_desde_inicio' => 67.3, 'distancia_desde_fin' => 252.7],
            ['nombre' => 'Campiello', 'distancia_desde_inicio' => 80.2, 'distancia_desde_fin' => 239.8],
            ['nombre' => 'Borres', 'distancia_desde_inicio' => 83.2, 'distancia_desde_fin' => 236.8],
            ['nombre' => 'Colinas de Arriba', 'distancia_desde_inicio' => 87.6, 'distancia_desde_fin' => 232.4],
            ['nombre' => 'Pola de Allande', 'distancia_desde_inicio' => 94.3, 'distancia_desde_fin' => 225.7],
            ['nombre' => 'Berducedo', 'distancia_desde_inicio' => 111.8, 'distancia_desde_fin' => 208.2],
            ['nombre' => 'La Mesa', 'distancia_desde_inicio' => 116.2, 'distancia_desde_fin' => 203.8],
            ['nombre' => 'Grandas de Salime', 'distancia_desde_inicio' => 132.2, 'distancia_desde_fin' => 187.8],
            ['nombre' => 'Castro', 'distancia_desde_inicio' => 137.3, 'distancia_desde_fin' => 182.7],
            ['nombre' => 'A Fonsagrada', 'distancia_desde_inicio' => 157.4, 'distancia_desde_fin' => 162.6],
            ['nombre' => 'O Cádavo', 'distancia_desde_inicio' => 181.7, 'distancia_desde_fin' => 138.3],
            ['nombre' => 'Castroverde', 'distancia_desde_inicio' => 189.5, 'distancia_desde_fin' => 130.5],
            ['nombre' => 'Vilar de Cas', 'distancia_desde_inicio' => 195.6, 'distancia_desde_fin' => 124.4],
            ['nombre' => 'Lugo', 'distancia_desde_inicio' => 211.2, 'distancia_desde_fin' => 108.8],
            ['nombre' => 'San Román de Retorta', 'distancia_desde_inicio' => 229.5, 'distancia_desde_fin' => 90.5],
            ['nombre' => 'A Ponte Ferreira', 'distancia_desde_inicio' => 237.7, 'distancia_desde_fin' => 82.3],
            ['nombre' => 'As Seixas', 'distancia_desde_inicio' => 243.3, 'distancia_desde_fin' => 76.7],
            ['nombre' => 'Melide', 'distancia_desde_inicio' => 257.7, 'distancia_desde_fin' => 62.3],
            ['nombre' => 'Boente', 'distancia_desde_inicio' => 263.3, 'distancia_desde_fin' => 56.7],
            ['nombre' => 'Ribadiso de Baixo', 'distancia_desde_inicio' => 268.7, 'distancia_desde_fin' => 51.3],
            ['nombre' => 'Arzúa', 'distancia_desde_inicio' => 271.8, 'distancia_desde_fin' => 48.2],
            ['nombre' => 'A Salceda', 'distancia_desde_inicio' => 283.1, 'distancia_desde_fin' => 36.9],
            ['nombre' => 'Santa Irene', 'distancia_desde_inicio' => 287.7, 'distancia_desde_fin' => 32.3],
            ['nombre' => 'O Pedrouzo', 'distancia_desde_inicio' => 291.1, 'distancia_desde_fin' => 28.9],
            ['nombre' => 'Lavacolla', 'distancia_desde_inicio' => 300.6, 'distancia_desde_fin' => 19.4],
            ['nombre' => 'Monte do Gozo', 'distancia_desde_inicio' => 306.1, 'distancia_desde_fin' => 13.9],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 310.5, 'distancia_desde_fin' => 0],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }
    }
}
