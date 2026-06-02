<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class ViaVerdeDelAceiteSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== 1. CREAR RUTA ====================
        $ruta = Ruta::updateOrCreate(
            ['nombre' => 'Vía Verde del Aceite · Jaén a Córdoba'],
            [
                'dificultad' => 'baja',
                'inicio' => 'Jaén',
                'fin' => 'Puente Genil (Córdoba)',
                'kilometros' => 127,
                'imagen' => 'https://www.viasverdes.com/images/vias/aceite/aceite.jpg',
                'activo' => true
            ]
        );

        $ruta->localizaciones()->delete();

        // ==================== 2. LOCALIZACIONES ====================
        $localizaciones = [
            ['nombre' => 'Jaén', 'distancia_desde_inicio' => 0, 'distancia_desde_fin' => 127, 'descripcion' => 'Capital del aceite de oliva. Inicio de la vía verde.'],
            ['nombre' => 'Torredelcampo', 'distancia_desde_inicio' => 12.0, 'distancia_desde_fin' => 115],
            ['nombre' => 'Torre del Campo', 'distancia_desde_inicio' => 20.0, 'distancia_desde_fin' => 107],
            ['nombre' => 'Martos', 'distancia_desde_inicio' => 28.0, 'distancia_desde_fin' => 99, 'descripcion' => 'Ciudad olivarera. Castillo de la Villa.'],
            ['nombre' => 'Alcaudete', 'distancia_desde_inicio' => 40.0, 'distancia_desde_fin' => 87, 'descripcion' => 'Castillo calatravo del siglo XIII.'],
            ['nombre' => 'Castillo de Locubín', 'distancia_desde_inicio' => 52.0, 'distancia_desde_fin' => 75],
            ['nombre' => 'Alcalá la Real', 'distancia_desde_inicio' => 62.0, 'distancia_desde_fin' => 65, 'descripcion' => 'Fortaleza de la Mota. Conjunto monumental.'],
            ['nombre' => 'Fuente-Tójar', 'distancia_desde_inicio' => 72.0, 'distancia_desde_fin' => 55],
            ['nombre' => 'Priego de Córdoba', 'distancia_desde_inicio' => 82.0, 'distancia_desde_fin' => 45, 'descripcion' => 'Capital del barroco cordobés.'],
            ['nombre' => 'Carcabuey', 'distancia_desde_inicio' => 94.0, 'distancia_desde_fin' => 33],
            ['nombre' => 'Doña Mencía', 'distancia_desde_inicio' => 104.0, 'distancia_desde_fin' => 23],
            ['nombre' => 'Nueva Carteya', 'distancia_desde_inicio' => 110.0, 'distancia_desde_fin' => 17],
            ['nombre' => 'Puente Genil', 'distancia_desde_inicio' => 127.0, 'distancia_desde_fin' => 0, 'descripcion' => 'Final de la vía verde.'],
        ];

        foreach ($localizaciones as $loc) {
            $ruta->localizaciones()->create($loc);
        }

        $localizacionesMap = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        // ==================== 3. ALOJAMIENTOS ====================
        $alojamientos = [
            // Jaén
            ['localizacion' => 'Jaén', 'nombre' => 'Albergue Inturjoven', 'tipo' => 'albergue', 'telefono' => '+34 953 22 00 00'],
            ['localizacion' => 'Jaén', 'nombre' => 'Hotel Xauen', 'tipo' => 'hotel', 'telefono' => '+34 953 22 00 01'],

            // Martos
            ['localizacion' => 'Martos', 'nombre' => 'Hotel Torrepalma', 'tipo' => 'hotel', 'telefono' => '+34 953 55 00 00'],

            // Alcaudete
            ['localizacion' => 'Alcaudete', 'nombre' => 'Hotel El Carmen', 'tipo' => 'hotel', 'telefono' => '+34 953 56 00 00'],

            // Alcalá la Real
            ['localizacion' => 'Alcalá la Real', 'nombre' => 'Hotel Santa María del Paso', 'tipo' => 'hotel', 'telefono' => '+34 953 58 00 00'],

            // Priego de Córdoba
            ['localizacion' => 'Priego de Córdoba', 'nombre' => 'Hotel Zahorí', 'tipo' => 'hotel', 'telefono' => '+34 957 70 00 00'],
            ['localizacion' => 'Priego de Córdoba', 'nombre' => 'Hostal Al-Medina', 'tipo' => 'hostal', 'telefono' => '+34 957 70 00 01'],

            // Puente Genil
            ['localizacion' => 'Puente Genil', 'nombre' => 'Hotel Don Gonzalo', 'tipo' => 'hotel', 'telefono' => '+34 957 60 00 00'],
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

        $this->command->info('Vía Verde del Aceite creada con ' . count($localizaciones) . ' localizaciones y ' . count($alojamientos) . ' alojamientos.');
    }
}
