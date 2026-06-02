<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CaminoNorteAlojamientosSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener la ruta del Camino del Norte
        $ruta = Ruta::where('nombre', 'Camino del Norte')->first();

        if (!$ruta) {
            $this->command->error('Primero ejecuta CaminoNorteSeeder para crear la ruta');
            return;
        }

        // Mapear nombres de localizaciones con sus IDs reales
        $localizaciones = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // Albergues y hoteles reales organizados por localización
        $alojamientos = [
            // IRÚN
            [
                'localizacion' => 'Irún',
                'nombre' => 'Albergue de Peregrinos Jakobi',
                'direccion' => 'C/ Lucas de Berroa, 18',
                'tipo' => 'albergue',
                'telefono' => '+34 640 361 640',
                'email' => null,
                'enlace' => null,
            ],
            [
                'localizacion' => 'Irún',
                'nombre' => 'Albergue Turístico Goikoerrota',
                'direccion' => 'Barrio Jaizubia, 14, Hondarribia',
                'tipo' => 'albergue',
                'telefono' => '+34 943 64 38 84',
                'email' => null,
                'enlace' => null,
            ],
            // SAN SEBASTIÁN
            [
                'localizacion' => 'San Sebastián',
                'nombre' => 'Intelier Villa Katalina',
                'direccion' => 'Centro de San Sebastián',
                'tipo' => 'hotel',
                'telefono' => null,
                'email' => null,
                'enlace' => 'https://www.intelier.com',
            ],
            [
                'localizacion' => 'San Sebastián',
                'nombre' => 'Catalonia Donosti',
                'direccion' => 'San Sebastián',
                'tipo' => 'hotel',
                'telefono' => null,
                'email' => null,
                'enlace' => 'https://www.cataloniahotels.com',
            ],
            [
                'localizacion' => 'San Sebastián',
                'nombre' => 'Albergue Juvenil Ulía',
                'direccion' => 'Paseo de Ulía, 297',
                'tipo' => 'albergue',
                'telefono' => '+34 943 48 34 80',
                'email' => null,
                'enlace' => null,
            ],
            [
                'localizacion' => 'San Sebastián',
                'nombre' => 'Albergue de Peregrinos Ikastola Jakintza',
                'direccion' => 'Calle de la Escolta Real, 12',
                'tipo' => 'albergue',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            [
                'localizacion' => 'San Sebastián',
                'nombre' => 'Pensión Garibai',
                'direccion' => 'Centro de San Sebastián',
                'tipo' => 'hostal',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // ZARAUTZ
            [
                'localizacion' => 'Zarautz',
                'nombre' => 'Zarautz Hostel',
                'direccion' => 'Zarautz',
                'tipo' => 'hostal',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // BILBAO
            [
                'localizacion' => 'Bilbao',
                'nombre' => 'Letoh Letoh Bilbao',
                'direccion' => 'Casco Viejo de Bilbao',
                'tipo' => 'hotel',
                'telefono' => null,
                'email' => null,
                'enlace' => 'https://www.letohletoh.com',
            ],
            [
                'localizacion' => 'Bilbao',
                'nombre' => 'Catalonia Gran Vía Bilbao',
                'direccion' => 'Gran Vía, Bilbao',
                'tipo' => 'hotel',
                'telefono' => null,
                'email' => null,
                'enlace' => 'https://www.cataloniahotels.com',
            ],
            // LAREDO
            [
                'localizacion' => 'Laredo',
                'nombre' => 'Albergue Bajamar',
                'direccion' => 'Laredo',
                'tipo' => 'albergue',
                'telefono' => '+34 656 552 710',
                'email' => 'bajamarlaredo@gmail.com',
                'enlace' => null,
            ],
            // GÜEMES
            [
                'localizacion' => 'Güemes (Santander)',
                'nombre' => 'Albergue de Güemes',
                'direccion' => 'Güemes',
                'tipo' => 'albergue',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // SANTANDER
            [
                'localizacion' => 'Santander',
                'nombre' => 'Albergue Municipal Santos Mártires',
                'direccion' => 'Santander',
                'tipo' => 'albergue',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // SOBRADO DOS MONXES
            [
                'localizacion' => 'Sobrado dos Monxes',
                'nombre' => 'Albergue de Sobrado',
                'direccion' => 'Sobrado dos Monxes',
                'tipo' => 'albergue',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // ARZÚA
            [
                'localizacion' => 'Arzúa',
                'nombre' => 'Hotel Arzúa',
                'direccion' => 'Arzúa',
                'tipo' => 'hotel',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            [
                'localizacion' => 'Arzúa',
                'nombre' => 'La Puerta de Arzúa',
                'direccion' => 'Arzúa',
                'tipo' => 'hostal',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // BAAMONDE
            [
                'localizacion' => 'Baamonde',
                'nombre' => 'Km101',
                'direccion' => 'Baamonde',
                'tipo' => 'hostal',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
            // MONDOÑEDO
            [
                'localizacion' => 'Mondoñedo',
                'nombre' => 'Albergue de Mondoñedo',
                'direccion' => 'Mondoñedo',
                'tipo' => 'albergue',
                'telefono' => null,
                'email' => null,
                'enlace' => null,
            ],
        ];

        $contador = 0;
        foreach ($alojamientos as $item) {
            $localizacion = $localizaciones[$item['localizacion']] ?? null;

            if ($localizacion) {
                Alojamiento::create([
                    'localizacion_id' => $localizacion->id,
                    'nombre' => $item['nombre'],
                    'direccion' => $item['direccion'],
                    'tipo' => $item['tipo'],
                    'telefono' => $item['telefono'],
                    'email' => $item['email'],
                    'enlace' => $item['enlace'],
                    'activo' => true,
                ]);
                $contador++;
            } else {
                $this->command->warn("Localización no encontrada: {$item['localizacion']}");
            }
        }

        $this->command->info("Se añadieron $contador alojamientos reales al Camino del Norte.");
    }
}
