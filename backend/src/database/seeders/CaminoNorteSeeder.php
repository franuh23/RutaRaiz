<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;

class CaminoNorteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear la ruta
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Camino del Norte'],
            [
                'dificultad' => 'alta',
                'inicio' => 'Irún',
                'fin' => 'Santiago de Compostela',
                'kilometros' => 824,
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Mapa_del_Camino_del_Norte.svg/800px-Mapa_del_Camino_del_Norte.svg.png',
                'activo' => true
            ]
        );

        // Eliminar localizaciones antiguas para evitar duplicados
        $ruta->localizaciones()->delete();

        // Insertar localizaciones
        $localizaciones = [
            // ========== PAÍS VASCO (Irún a Bilbao) ==========
            ['nombre' => 'Irún', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 824, 'descripcion' => 'Punto de partida oficial en la frontera con Francia.'],
            ['nombre' => 'San Sebastián', 'distancia_desde_inicio' => 25.0, 'distancia_desde_fin' => 799, 'descripcion' => 'La hermosa Donostia, famosa por su playa de la Concha.'],
            ['nombre' => 'Orio', 'distancia_desde_inicio' => 40.0, 'distancia_desde_fin' => 784],
            ['nombre' => 'Zarautz', 'distancia_desde_inicio' => 46.0, 'distancia_desde_fin' => 778, 'descripcion' => 'Famoso destino de surf en el País Vasco.'],
            ['nombre' => 'Getaria', 'distancia_desde_inicio' => 55.0, 'distancia_desde_fin' => 769],
            ['nombre' => 'Zumaia', 'distancia_desde_inicio' => 60.0, 'distancia_desde_fin' => 764],
            ['nombre' => 'Deba', 'distancia_desde_inicio' => 68.0, 'distancia_desde_fin' => 756],
            ['nombre' => 'Markina-Xemein', 'distancia_desde_inicio' => 86.0, 'distancia_desde_fin' => 738, 'descripcion' => 'Etapa interior con paisajes de montaña y valles verdes.'],
            ['nombre' => 'Gernika', 'distancia_desde_inicio' => 109.0, 'distancia_desde_fin' => 715, 'descripcion' => 'Villa vasca conocida por su árbol simbólico y el bombardeo de 1937.'],
            ['nombre' => 'Lezama', 'distancia_desde_inicio' => 125.0, 'distancia_desde_fin' => 699],
            ['nombre' => 'Bilbao', 'distancia_desde_inicio' => 139.0, 'distancia_desde_fin' => 685, 'descripcion' => 'Gran urbe industrial con el famoso Museo Guggenheim.'],

            // ========== CANTABRIA (Bilbao a Unquera) ==========
            ['nombre' => 'Portugalete', 'distancia_desde_inicio' => 153.0, 'distancia_desde_fin' => 671],
            ['nombre' => 'Castro Urdiales', 'distancia_desde_inicio' => 176.0, 'distancia_desde_fin' => 648],
            ['nombre' => 'Laredo', 'distancia_desde_inicio' => 198.0, 'distancia_desde_fin' => 626, 'descripcion' => 'Conocida por su larga playa y el casco histórico.'],
            ['nombre' => 'Santander', 'distancia_desde_inicio' => 232.0, 'distancia_desde_fin' => 592, 'descripcion' => 'Capital de Cantabria, sede del Palacio de la Magdalena.'],
            ['nombre' => 'Santillana del Mar', 'distancia_desde_inicio' => 259.0, 'distancia_desde_fin' => 565, 'descripcion' => 'Considerada uno de los pueblos más bonitos de España.'],
            ['nombre' => 'Comillas', 'distancia_desde_inicio' => 280.0, 'distancia_desde_fin' => 544, 'descripcion' => 'Localidad modernista con el famoso "Capricho de Gaudí".'],
            ['nombre' => 'San Vicente de la Barquera', 'distancia_desde_inicio' => 304.0, 'distancia_desde_fin' => 520],
            ['nombre' => 'Unquera', 'distancia_desde_inicio' => 323.0, 'distancia_desde_fin' => 501],

            // ========== ASTURIAS (Unquera a Ribadeo) ==========
            ['nombre' => 'Llanes', 'distancia_desde_inicio' => 337.0, 'distancia_desde_fin' => 487],
            ['nombre' => 'Ribadesella', 'distancia_desde_inicio' => 370.0, 'distancia_desde_fin' => 454],
            ['nombre' => 'Colunga', 'distancia_desde_inicio' => 390.0, 'distancia_desde_fin' => 434],
            ['nombre' => 'Villaviciosa', 'distancia_desde_inicio' => 406.0, 'distancia_desde_fin' => 418],
            ['nombre' => 'Gijón', 'distancia_desde_inicio' => 431.0, 'distancia_desde_fin' => 393, 'descripcion' => 'Ciudad asturiana con un importante pasado romano.'],
            ['nombre' => 'Avilés', 'distancia_desde_inicio' => 457.0, 'distancia_desde_fin' => 367],
            ['nombre' => 'Muros de Nalón', 'distancia_desde_inicio' => 481.0, 'distancia_desde_fin' => 343],
            ['nombre' => 'Cudillero', 'distancia_desde_inicio' => 497.0, 'distancia_desde_fin' => 327],
            ['nombre' => 'Luarca', 'distancia_desde_inicio' => 527.0, 'distancia_desde_fin' => 297, 'descripcion' => 'Pueblo costero conocido como "La Villa Blanca de la Costa Verde".'],
            ['nombre' => 'La Caridad', 'distancia_desde_inicio' => 554.0, 'distancia_desde_fin' => 270],
            ['nombre' => 'Ribadeo', 'distancia_desde_inicio' => 574.0, 'distancia_desde_fin' => 250, 'descripcion' => 'Puerta de entrada a Galicia. Famoso por sus playas y la ría.'],

            // ========== GALICIA (Ribadeo a Arzúa) ==========
            ['nombre' => 'Lourenzá', 'distancia_desde_inicio' => 596.0, 'distancia_desde_fin' => 228],
            ['nombre' => 'Mondoñedo', 'distancia_desde_inicio' => 611.0, 'distancia_desde_fin' => 213],
            ['nombre' => 'Abadín', 'distancia_desde_inicio' => 635.0, 'distancia_desde_fin' => 189],
            ['nombre' => 'Vilalba', 'distancia_desde_inicio' => 655.0, 'distancia_desde_fin' => 169],
            ['nombre' => 'Baamonde', 'distancia_desde_inicio' => 673.0, 'distancia_desde_fin' => 151],
            ['nombre' => 'Sobrado dos Monxes', 'distancia_desde_inicio' => 700.0, 'distancia_desde_fin' => 124, 'descripcion' => 'Famoso por su impresionante monasterio cisterciense.'],
            ['nombre' => 'Arzúa', 'distancia_desde_inicio' => 731.0, 'distancia_desde_fin' => 93, 'descripcion' => 'Punto de unión con el Camino Francés, famoso por su queso.'],

            // ========== UNIÓN CON EL CAMINO FRANCÉS (Arzúa a Santiago) ==========
            ['nombre' => 'Arca (O Pedrouzo)', 'distancia_desde_inicio' => 764.0, 'distancia_desde_fin' => 60],
            ['nombre' => 'Monte do Gozo', 'distancia_desde_inicio' => 809.0, 'distancia_desde_fin' => 15, 'descripcion' => 'Monte desde donde los peregrinos divisan por primera vez las torres de la Catedral.'],
            ['nombre' => 'Santiago de Compostela', 'distancia_desde_inicio' => 824.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final del Camino. Tumba del Apóstol Santiago.']
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }
    }
}