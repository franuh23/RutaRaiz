<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;

class GR11Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la ruta
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'GR-11 · Senda Pirenaica'],
            [
                'dificultad' => 'alta',
                'inicio' => 'Cap de Creus',
                'fin' => 'Cabo de Higuer',
                'kilometros' => 842,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/GR_11_Logo.svg/800px-GR_11_Logo.svg.png',
                'activo' => true
            ]
        );

        // Eliminar localizaciones antiguas para evitar duplicados
        $ruta->localizaciones()->delete();


        $localizaciones = [
            // ========== CATALUÑA (Cap de Creus a Andorra) ==========
            ['nombre' => 'Cap de Creus', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 842, 'descripcion' => 'Punto más oriental de la península. Inicio oficial del GR-11. Faro y paisaje lunar.'],
            ['nombre' => 'Cala Taballera', 'distancia_desde_inicio' => 6.2, 'distancia_desde_fin' => 835.8],
            ['nombre' => 'Cala Prona', 'distancia_desde_inicio' => 10.5, 'distancia_desde_fin' => 831.5],
            ['nombre' => 'Port de la Selva', 'distancia_desde_inicio' => 15.8, 'distancia_desde_fin' => 826.2],
            ['nombre' => 'Llançà', 'distancia_desde_inicio' => 23.5, 'distancia_desde_fin' => 818.5],
            ['nombre' => 'Vilamaniscle', 'distancia_desde_inicio' => 30.2, 'distancia_desde_fin' => 811.8],
            ['nombre' => 'La Jonquera', 'distancia_desde_inicio' => 38.7, 'distancia_desde_fin' => 803.3, 'descripcion' => 'Pueblo fronterizo. Inicio del tramo pirenaico.'],
            ['nombre' => 'Coll del Pertús', 'distancia_desde_inicio' => 44.0, 'distancia_desde_fin' => 798.0],
            ['nombre' => 'La Vajol', 'distancia_desde_inicio' => 50.3, 'distancia_desde_fin' => 791.7],
            ['nombre' => 'Agliana', 'distancia_desde_inicio' => 56.8, 'distancia_desde_fin' => 785.2],
            ['nombre' => 'Albanyà', 'distancia_desde_inicio' => 65.5, 'distancia_desde_fin' => 776.5],
            ['nombre' => 'Bassegoda', 'distancia_desde_inicio' => 74.0, 'distancia_desde_fin' => 768.0],
            ['nombre' => 'Beget', 'distancia_desde_inicio' => 85.2, 'distancia_desde_fin' => 756.8, 'descripcion' => 'Pueblo medieval con puente románico.'],
            ['nombre' => 'Camprodon', 'distancia_desde_inicio' => 98.5, 'distancia_desde_fin' => 743.5],
            ['nombre' => 'Setcases', 'distancia_desde_inicio' => 110.0, 'distancia_desde_fin' => 732.0],
            ['nombre' => 'Refugi Ulldeter', 'distancia_desde_inicio' => 125.5, 'distancia_desde_fin' => 716.5, 'descripcion' => 'Refugio bajo el pico más alto de Cataluña (Pica d\'Estats).'],
            ['nombre' => 'Planoles', 'distancia_desde_inicio' => 140.2, 'distancia_desde_fin' => 701.8],
            ['nombre' => 'Ribes de Freser', 'distancia_desde_inicio' => 150.8, 'distancia_desde_fin' => 691.2],
            ['nombre' => 'Queralbs', 'distancia_desde_inicio' => 158.0, 'distancia_desde_fin' => 684.0],
            ['nombre' => 'Coma de Vaca', 'distancia_desde_inicio' => 168.5, 'distancia_desde_fin' => 673.5],
            ['nombre' => 'Refugi Malniu', 'distancia_desde_inicio' => 182.0, 'distancia_desde_fin' => 660.0],
            ['nombre' => 'Meranges', 'distancia_desde_inicio' => 195.0, 'distancia_desde_fin' => 647.0],
            ['nombre' => 'Lles de Cerdanya', 'distancia_desde_inicio' => 208.5, 'distancia_desde_fin' => 633.5],
            ['nombre' => 'Refugi de la Pleta del Prat', 'distancia_desde_inicio' => 220.0, 'distancia_desde_fin' => 622.0],
            ['nombre' => 'Estany de la Pera', 'distancia_desde_inicio' => 232.0, 'distancia_desde_fin' => 610.0],

            // ========== ANDORRA ==========
            ['nombre' => 'El Pas de la Casa', 'distancia_desde_inicio' => 245.5, 'distancia_desde_fin' => 596.5, 'descripcion' => 'Puerto de montaña y frontera con Andorra.'],
            ['nombre' => 'Grau Roig', 'distancia_desde_inicio' => 252.0, 'distancia_desde_fin' => 590.0],
            ['nombre' => 'Encamp', 'distancia_desde_inicio' => 265.5, 'distancia_desde_fin' => 576.5, 'descripcion' => 'Ciudad andorrana. Acceso a funiculares.'],
            ['nombre' => 'Canillo', 'distancia_desde_inicio' => 278.0, 'distancia_desde_fin' => 564.0],
            ['nombre' => 'Refugi de Comapedrosa', 'distancia_desde_inicio' => 295.0, 'distancia_desde_fin' => 547.0],
            ['nombre' => 'Arans', 'distancia_desde_inicio' => 310.0, 'distancia_desde_fin' => 532.0],

            // ========== ARAGÓN (Pirineo Aragonés) ==========
            ['nombre' => 'Refugi de Certascan', 'distancia_desde_inicio' => 330.0, 'distancia_desde_fin' => 512.0],
            ['nombre' => 'Areu', 'distancia_desde_inicio' => 348.0, 'distancia_desde_fin' => 494.0],
            ['nombre' => 'Espot', 'distancia_desde_inicio' => 362.5, 'distancia_desde_fin' => 479.5],
            ['nombre' => 'Refugi Ernest Mallafré', 'distancia_desde_inicio' => 380.0, 'distancia_desde_fin' => 462.0, 'descripcion' => 'En pleno Parque Nacional de Aigüestortes.'],
            ['nombre' => 'Refugi Colomina', 'distancia_desde_inicio' => 398.0, 'distancia_desde_fin' => 444.0],
            ['nombre' => 'Refugi de la Restanca', 'distancia_desde_inicio' => 418.0, 'distancia_desde_fin' => 424.0],
            ['nombre' => 'Refugi Cap de Llauset', 'distancia_desde_inicio' => 438.0, 'distancia_desde_fin' => 404.0],
            ['nombre' => 'Refugi de Viadós', 'distancia_desde_inicio' => 455.0, 'distancia_desde_fin' => 387.0],
            ['nombre' => 'Parque Nacional de Ordesa', 'distancia_desde_inicio' => 470.0, 'distancia_desde_fin' => 372.0, 'descripcion' => 'Entrada al valle de Ordesa.'],
            ['nombre' => 'Refugi de Pineta', 'distancia_desde_inicio' => 488.0, 'distancia_desde_fin' => 354.0],
            ['nombre' => 'Refugio de Bujaruelo', 'distancia_desde_inicio' => 506.0, 'distancia_desde_fin' => 336.0],
            ['nombre' => 'Refugio de Bachimaña', 'distancia_desde_inicio' => 525.0, 'distancia_desde_fin' => 317.0],
            ['nombre' => 'Refugio de Respomuso', 'distancia_desde_inicio' => 542.0, 'distancia_desde_fin' => 300.0],
            ['nombre' => 'Sallent de Gállego', 'distancia_desde_inicio' => 560.0, 'distancia_desde_fin' => 282.0, 'descripcion' => 'Pueblo junto al embalse de Lanuza.'],
            ['nombre' => 'Candanchú', 'distancia_desde_inicio' => 580.0, 'distancia_desde_fin' => 262.0, 'descripcion' => 'Estación de esquí. Frontera con Francia.'],
            ['nombre' => 'Refugio de Lizara', 'distancia_desde_inicio' => 600.0, 'distancia_desde_fin' => 242.0],

            // ========== PAÍS VASCO / NAVARRA ==========
            ['nombre' => 'Zuriza', 'distancia_desde_inicio' => 620.0, 'distancia_desde_fin' => 222.0, 'descripcion' => 'Camping base en el valle de Ansó.'],
            ['nombre' => 'Isaba', 'distancia_desde_inicio' => 642.0, 'distancia_desde_fin' => 200.0, 'descripcion' => 'Pueblo navarro con arquitectura tradicional.'],
            ['nombre' => 'Ochagavía', 'distancia_desde_inicio' => 665.0, 'distancia_desde_fin' => 177.0],
            ['nombre' => 'Refugio de Belagua', 'distancia_desde_inicio' => 685.0, 'distancia_desde_fin' => 157.0],
            ['nombre' => 'Roncesvalles', 'distancia_desde_inicio' => 710.0, 'distancia_desde_fin' => 132.0, 'descripcion' => 'Icono del Camino de Santiago. Colegiata.'],
            ['nombre' => 'Elizondo', 'distancia_desde_inicio' => 735.0, 'distancia_desde_fin' => 107.0],
            ['nombre' => 'Santesteban', 'distancia_desde_inicio' => 755.0, 'distancia_desde_fin' => 87.0],
            ['nombre' => 'Puerto de Otsondo', 'distancia_desde_inicio' => 770.0, 'distancia_desde_fin' => 72.0],
            ['nombre' => 'Zugarramurdi', 'distancia_desde_inicio' => 790.0, 'distancia_desde_fin' => 52.0, 'descripcion' => 'Famoso por sus cuevas y aquelarres.'],
            ['nombre' => 'Puerto de Ibardin', 'distancia_desde_inicio' => 805.0, 'distancia_desde_fin' => 37.0],

            // ========== FINAL ==========
            ['nombre' => 'Hondarribia', 'distancia_desde_inicio' => 825.0, 'distancia_desde_fin' => 17.0],
            ['nombre' => 'Cabo de Higuer', 'distancia_desde_inicio' => 842.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del GR-11. Faro y acantilados con vistas al mar Cantábrico.']
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }
    }
}
