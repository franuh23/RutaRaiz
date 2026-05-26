<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;

class CaminoFrancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la ruta
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino Francés'],
            [
                'dificultad' => 'media',
                'inicio' => 'Roncesvalles',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 794,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/50/Mapa_del_Camino_de_Santiago_Franc%C3%A9s.png/800px-Mapa_del_Camino_de_Santiago_Franc%C3%A9s.png',
                'activo' => true
            ]
        );

        // Eliminar localizaciones antiguas
        $ruta->localizaciones()->delete();

        // Insertar localizaciones
        $localizaciones = [
            // ========== NAVARRA ==========
            ['nombre' => 'Roncesvalles', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 794, 'descripcion' => 'Puerta de entrada del Camino Francés a España. Real Colegiata de Santa María.'],
            ['nombre' => 'Burguete', 'distancia_desde_inicio' => 3.2, 'distancia_desde_fin' => 790.8],
            ['nombre' => 'Espinal', 'distancia_desde_inicio' => 6.7, 'distancia_desde_fin' => 787.3],
            ['nombre' => 'Bizkarreta', 'distancia_desde_inicio' => 12.1, 'distancia_desde_fin' => 781.9],
            ['nombre' => 'Lintzoain', 'distancia_desde_inicio' => 14.4, 'distancia_desde_fin' => 779.6],
            ['nombre' => 'Zubiri', 'distancia_desde_inicio' => 21.4, 'distancia_desde_fin' => 772.6, 'descripcion' => 'Puente sobre el río Arga.'],
            ['nombre' => 'Larrasoaña', 'distancia_desde_inicio' => 26.5, 'distancia_desde_fin' => 767.5],
            ['nombre' => 'Zuriain', 'distancia_desde_inicio' => 30.8, 'distancia_desde_fin' => 763.2],
            ['nombre' => 'Iroz', 'distancia_desde_inicio' => 34.2, 'distancia_desde_fin' => 759.8],
            ['nombre' => 'Villava', 'distancia_desde_inicio' => 38.5, 'distancia_desde_fin' => 755.5],
            ['nombre' => 'Pamplona', 'distancia_desde_inicio' => 41.8, 'distancia_desde_fin' => 752.2, 'descripcion' => 'Capital navarra. Famosa por los Sanfermines.'],
            ['nombre' => 'Cizur Menor', 'distancia_desde_inicio' => 46.9, 'distancia_desde_fin' => 747.1],
            ['nombre' => 'Zariquiegui', 'distancia_desde_inicio' => 53.2, 'distancia_desde_fin' => 740.8],
            ['nombre' => 'Alto del Perdón', 'distancia_desde_inicio' => 57.5, 'distancia_desde_fin' => 736.5, 'descripcion' => 'Mirador con esculturas de peregrinos.'],
            ['nombre' => 'Uterga', 'distancia_desde_inicio' => 60.9, 'distancia_desde_fin' => 733.1],
            ['nombre' => 'Muruzábal', 'distancia_desde_inicio' => 63.7, 'distancia_desde_fin' => 730.3],
            ['nombre' => 'Obanos', 'distancia_desde_inicio' => 65.2, 'distancia_desde_fin' => 728.8],
            ['nombre' => 'Puente la Reina', 'distancia_desde_inicio' => 67.3, 'distancia_desde_fin' => 726.7, 'descripcion' => 'Puente románico sobre el río Arga. Unión con el Camino Aragonés.'],
            ['nombre' => 'Mañeru', 'distancia_desde_inicio' => 72.5, 'distancia_desde_fin' => 721.5],
            ['nombre' => 'Cirauqui', 'distancia_desde_inicio' => 75.6, 'distancia_desde_fin' => 718.4],
            ['nombre' => 'Lorca', 'distancia_desde_inicio' => 79.3, 'distancia_desde_fin' => 714.7],
            ['nombre' => 'Villatuerta', 'distancia_desde_inicio' => 83.1, 'distancia_desde_fin' => 710.9],
            ['nombre' => 'Estella', 'distancia_desde_inicio' => 86.4, 'distancia_desde_fin' => 707.6, 'descripcion' => 'Ciudad medieval con palacios y monasterios.'],
            ['nombre' => 'Ayegui', 'distancia_desde_inicio' => 88.9, 'distancia_desde_fin' => 705.1],
            ['nombre' => 'Irache', 'distancia_desde_inicio' => 90.7, 'distancia_desde_fin' => 703.3, 'descripcion' => 'Monasterio y famosa Fuente del Vino.'],
            ['nombre' => 'Azqueta', 'distancia_desde_inicio' => 95.0, 'distancia_desde_fin' => 699.0],
            ['nombre' => 'Villamayor de Monjardín', 'distancia_desde_inicio' => 98.2, 'distancia_desde_fin' => 695.8],
            ['nombre' => 'Los Arcos', 'distancia_desde_inicio' => 102.9, 'distancia_desde_fin' => 691.1],

            // ========== LA RIOJA ==========
            ['nombre' => 'Sansol', 'distancia_desde_inicio' => 108.6, 'distancia_desde_fin' => 685.4],
            ['nombre' => 'Torres del Río', 'distancia_desde_inicio' => 110.8, 'distancia_desde_fin' => 683.2],
            ['nombre' => 'Viana', 'distancia_desde_inicio' => 115.3, 'distancia_desde_fin' => 678.7],
            ['nombre' => 'Logroño', 'distancia_desde_inicio' => 121.7, 'distancia_desde_fin' => 672.3, 'descripcion' => 'Capital de La Rioja. Famosa por sus bodegas y la Calle del Laurel.'],
            ['nombre' => 'Navarrete', 'distancia_desde_inicio' => 133.3, 'distancia_desde_fin' => 660.7],
            ['nombre' => 'Ventosa', 'distancia_desde_inicio' => 140.1, 'distancia_desde_fin' => 653.9],
            ['nombre' => 'Nájera', 'distancia_desde_inicio' => 147.9, 'distancia_desde_fin' => 646.1],
            ['nombre' => 'Azofra', 'distancia_desde_inicio' => 155.2, 'distancia_desde_fin' => 638.8],
            ['nombre' => 'Cirueña', 'distancia_desde_inicio' => 163.0, 'distancia_desde_fin' => 631.0],
            ['nombre' => 'Santo Domingo de la Calzada', 'distancia_desde_inicio' => 169.1, 'distancia_desde_fin' => 624.9, 'descripcion' => 'Catedral y el milagro del gallo y la gallina.'],

            // ========== CASTILLA Y LEÓN (BURGOS) ==========
            ['nombre' => 'Grañón', 'distancia_desde_inicio' => 182.0, 'distancia_desde_fin' => 612.0],
            ['nombre' => 'Redecilla del Camino', 'distancia_desde_inicio' => 187.6, 'distancia_desde_fin' => 606.4],
            ['nombre' => 'Castildelgado', 'distancia_desde_inicio' => 190.3, 'distancia_desde_fin' => 603.7],
            ['nombre' => 'Viloria de Rioja', 'distancia_desde_inicio' => 192.0, 'distancia_desde_fin' => 602.0],
            ['nombre' => 'Villamayor del Río', 'distancia_desde_inicio' => 194.5, 'distancia_desde_fin' => 599.5],
            ['nombre' => 'Belorado', 'distancia_desde_inicio' => 197.8, 'distancia_desde_fin' => 596.2],
            ['nombre' => 'Tosantos', 'distancia_desde_inicio' => 204.7, 'distancia_desde_fin' => 589.3],
            ['nombre' => 'Villambistia', 'distancia_desde_inicio' => 208.0, 'distancia_desde_fin' => 586.0],
            ['nombre' => 'Espinosa del Camino', 'distancia_desde_inicio' => 210.3, 'distancia_desde_fin' => 583.7],
            ['nombre' => 'Villafranca Montes de Oca', 'distancia_desde_inicio' => 215.4, 'distancia_desde_fin' => 578.6],
            ['nombre' => 'San Juan de Ortega', 'distancia_desde_inicio' => 228.9, 'distancia_desde_fin' => 565.1],
            ['nombre' => 'Agés', 'distancia_desde_inicio' => 235.0, 'distancia_desde_fin' => 559.0],
            ['nombre' => 'Burgos', 'distancia_desde_inicio' => 248.9, 'distancia_desde_fin' => 545.1, 'descripcion' => 'Catedral gótica, museo de la evolución.'],
            ['nombre' => 'Villalbilla', 'distancia_desde_inicio' => 257.4, 'distancia_desde_fin' => 536.6],
            ['nombre' => 'Tardajos', 'distancia_desde_inicio' => 261.7, 'distancia_desde_fin' => 532.3],
            ['nombre' => 'Rabé de las Calzadas', 'distancia_desde_inicio' => 265.0, 'distancia_desde_fin' => 529.0],
            ['nombre' => 'Hornillos del Camino', 'distancia_desde_inicio' => 272.0, 'distancia_desde_fin' => 522.0],
            ['nombre' => 'Castrojeriz', 'distancia_desde_inicio' => 286.5, 'distancia_desde_fin' => 507.5],
            ['nombre' => 'Itero del Castillo', 'distancia_desde_inicio' => 296.5, 'distancia_desde_fin' => 497.5],

            // ========== CASTILLA Y LEÓN (PALENCIA) ==========
            ['nombre' => 'Frómista', 'distancia_desde_inicio' => 302.8, 'distancia_desde_fin' => 491.2, 'descripcion' => 'Iglesia de San Martín (Románico puro).'],
            ['nombre' => 'Población de Campos', 'distancia_desde_inicio' => 309.7, 'distancia_desde_fin' => 484.3],
            ['nombre' => 'Villovieco', 'distancia_desde_inicio' => 316.5, 'distancia_desde_fin' => 477.5],
            ['nombre' => 'Carrión de los Condes', 'distancia_desde_inicio' => 325.6, 'distancia_desde_fin' => 468.4],
            ['nombre' => 'Calzadilla de la Cueza', 'distancia_desde_inicio' => 338.9, 'distancia_desde_fin' => 455.1],
            ['nombre' => 'Terradillos de los Templarios', 'distancia_desde_inicio' => 351.8, 'distancia_desde_fin' => 442.2],
            ['nombre' => 'Moratinos', 'distancia_desde_inicio' => 357.2, 'distancia_desde_fin' => 436.8],
            ['nombre' => 'Sahagún', 'distancia_desde_inicio' => 367.3, 'distancia_desde_fin' => 426.7, 'descripcion' => 'Monasterios, punto kilométrico central del Camino.'],

            // ========== CASTILLA Y LEÓN (LEÓN) ==========
            ['nombre' => 'Calzadilla de los Hermanillos', 'distancia_desde_inicio' => 376.0, 'distancia_desde_fin' => 418.0],
            ['nombre' => 'Bercianos del Real Camino', 'distancia_desde_inicio' => 390.2, 'distancia_desde_fin' => 403.8],
            ['nombre' => 'Mansilla de las Mulas', 'distancia_desde_inicio' => 405.0, 'distancia_desde_fin' => 389.0],
            ['nombre' => 'León', 'distancia_desde_inicio' => 424.5, 'distancia_desde_fin' => 369.5, 'descripcion' => 'Catedral gótica, San Isidoro y su Panteón Real.'],
            ['nombre' => 'Valverde de la Virgen', 'distancia_desde_inicio' => 434.9, 'distancia_desde_fin' => 359.1],
            ['nombre' => 'San Miguel del Camino', 'distancia_desde_inicio' => 441.9, 'distancia_desde_fin' => 352.1],
            ['nombre' => 'Villadangos del Páramo', 'distancia_desde_inicio' => 448.8, 'distancia_desde_fin' => 345.2],
            ['nombre' => 'Astorga', 'distancia_desde_inicio' => 467.8, 'distancia_desde_fin' => 326.2, 'descripcion' => 'Palacio de Gaudí, catedral y chocolate.'],
            ['nombre' => 'Murias de Rechivaldo', 'distancia_desde_inicio' => 475.0, 'distancia_desde_fin' => 319.0],
            ['nombre' => 'Santa Catalina de Somoza', 'distancia_desde_inicio' => 481.0, 'distancia_desde_fin' => 313.0],
            ['nombre' => 'El Ganso', 'distancia_desde_inicio' => 487.4, 'distancia_desde_fin' => 306.6],
            ['nombre' => 'Rabanal del Camino', 'distancia_desde_inicio' => 494.3, 'distancia_desde_fin' => 299.7],
            ['nombre' => 'Foncebadón', 'distancia_desde_inicio' => 504.1, 'distancia_desde_fin' => 289.9],
            ['nombre' => 'Cruz de Ferro', 'distancia_desde_inicio' => 507.0, 'distancia_desde_fin' => 287.0, 'descripcion' => 'Punto más alto y simbólico, tradición de dejar una piedra.'],
            ['nombre' => 'Ponferrada', 'distancia_desde_inicio' => 521.4, 'distancia_desde_fin' => 272.6, 'descripcion' => 'Castillo de los Templarios, museo de la Energía.'],

            // ========== CASTILLA Y LEÓN (EL BIERZO) ==========
            ['nombre' => 'Camponaraya', 'distancia_desde_inicio' => 531.0, 'distancia_desde_fin' => 263.0],
            ['nombre' => 'Pieros', 'distancia_desde_inicio' => 535.0, 'distancia_desde_fin' => 259.0],
            ['nombre' => 'Villafranca del Bierzo', 'distancia_desde_inicio' => 541.4, 'distancia_desde_fin' => 252.6, 'descripcion' => 'Puerta del Perdón. Conjunto histórico monumental.'],
            ['nombre' => 'Trabadelo', 'distancia_desde_inicio' => 550.8, 'distancia_desde_fin' => 243.2],
            ['nombre' => 'Vega de Valcarce', 'distancia_desde_inicio' => 562.3, 'distancia_desde_fin' => 231.7],
            ['nombre' => 'La Portela de Valcarce', 'distancia_desde_inicio' => 566.0, 'distancia_desde_fin' => 228.0],
            ['nombre' => 'Las Herrerías', 'distancia_desde_inicio' => 573.2, 'distancia_desde_fin' => 220.8],

            // ========== GALICIA (LUGO) ==========
            ['nombre' => 'O Cebreiro', 'distancia_desde_inicio' => 583.5, 'distancia_desde_fin' => 210.5, 'descripcion' => 'Pallozas celtas, iglesia prerrománica. Entrada a Galicia.'],
            ['nombre' => 'Liñares', 'distancia_desde_inicio' => 589.5, 'distancia_desde_fin' => 204.5],
            ['nombre' => 'Alto do Poio', 'distancia_desde_inicio' => 593.0, 'distancia_desde_fin' => 201.0],
            ['nombre' => 'Hospital da Condesa', 'distancia_desde_inicio' => 597.5, 'distancia_desde_fin' => 196.5],
            ['nombre' => 'Triacastela', 'distancia_desde_inicio' => 604.0, 'distancia_desde_fin' => 190.0],
            ['nombre' => 'San Xil', 'distancia_desde_inicio' => 613.0, 'distancia_desde_fin' => 181.0],
            ['nombre' => 'Sarria', 'distancia_desde_inicio' => 625.0, 'distancia_desde_fin' => 169.0, 'descripcion' => 'Punto de inicio más popular para obtener la Compostela (100 km).'],
            ['nombre' => 'Portomarín', 'distancia_desde_inicio' => 647.5, 'distancia_desde_fin' => 146.5, 'descripcion' => 'Vieja fortaleza, Iglesia de San Nicolás.'],
            ['nombre' => 'Palas de Rei', 'distancia_desde_inicio' => 672.4, 'distancia_desde_fin' => 121.6],
            ['nombre' => 'Melide', 'distancia_desde_inicio' => 697.0, 'distancia_desde_fin' => 97.0, 'descripcion' => 'Famoso por su pulpo. Cruce con el Camino del Norte.'],
            ['nombre' => 'Arzúa', 'distancia_desde_inicio' => 727.8, 'distancia_desde_fin' => 66.2, 'descripcion' => 'Capital del queso. Tierra de lácteos.'],
            ['nombre' => 'O Pedrouzo', 'distancia_desde_inicio' => 751.2, 'distancia_desde_fin' => 42.8],
            ['nombre' => 'Monte do Gozo', 'distancia_desde_inicio' => 777.5, 'distancia_desde_fin' => 16.5, 'descripcion' => 'Primera vista de las torres de la Catedral.'],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 794.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del Camino. Catedral del Apóstol Santiago.']
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }
    }
}
