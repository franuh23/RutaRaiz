<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class GR223AlojamientosSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = Ruta::where('nombre', 'GR-223 · Camí de Cavalls')->first();

        if (!$ruta) {
            $this->command->error('Primero ejecuta GR223Seeder');
            return;
        }

        $localizaciones = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        $alojamientos = [
            // ========== MAHÓN Y ALREDEDORES ==========
            ['localizacion' => 'Mahón', 'nombre' => 'Hotel Port Mahón', 'tipo' => 'hotel', 'telefono' => '+34 971 364 100'],
            ['localizacion' => 'Mahón', 'nombre' => 'Sant Roc Hotel', 'tipo' => 'hotel', 'telefono' => '+34 971 362 400'],
            ['localizacion' => 'Mahón', 'nombre' => 'Hostal La Isla', 'tipo' => 'hostal', 'telefono' => '+34 971 362 700'],

            // ========== CALA EN PORTER ==========
            ['localizacion' => 'Cala en Porter', 'nombre' => 'Hotel Sol del Este', 'tipo' => 'hotel', 'telefono' => '+34 971 377 130'],
            ['localizacion' => 'Cala en Porter', 'nombre' => 'Apartamentos Cala en Porter', 'tipo' => 'hostal', 'telefono' => '+34 971 377 194'],

            // ========== SON BOU ==========
            ['localizacion' => 'Son Bou', 'nombre' => 'Hotel Son Bou', 'tipo' => 'hotel', 'telefono' => '+34 971 372 128'],
            ['localizacion' => 'Son Bou', 'nombre' => 'Apartamentos Son Bou', 'tipo' => 'hostal', 'telefono' => '+34 971 372 150'],

            // ========== CIUTADELLA ==========
            ['localizacion' => 'Ciutadella', 'nombre' => 'Hotel Ciutadella', 'tipo' => 'hotel', 'telefono' => '+34 971 380 611'],
            ['localizacion' => 'Ciutadella', 'nombre' => 'Hostal Sa Prensa', 'tipo' => 'hostal', 'telefono' => '+34 971 382 441'],
            ['localizacion' => 'Ciutadella', 'nombre' => 'Mon Hotel', 'tipo' => 'hotel', 'telefono' => '+34 971 384 423'],

            // ========== CALA MORELL ==========
            ['localizacion' => 'Cala Morell', 'nombre' => 'Hotel Cala Morell', 'tipo' => 'hotel', 'telefono' => '+34 971 389 712'],

            // ========== FORNELLS ==========
            ['localizacion' => 'Fornells', 'nombre' => 'Hotel El Fornells', 'tipo' => 'hotel', 'telefono' => '+34 971 376 753'],
            ['localizacion' => 'Fornells', 'nombre' => 'Hostal Port Fornells', 'tipo' => 'hostal', 'telefono' => '+34 971 376 518'],

            // ========== ES MERCADAL ==========
            ['localizacion' => 'Es Mercadal', 'nombre' => 'Hotel Rural Sant Ignasi', 'tipo' => 'hotel', 'telefono' => '+34 971 375 103'],

            // ========== ES GRAU ==========
            ['localizacion' => 'Es Grau', 'nombre' => 'Hotel Rural Son Vell', 'tipo' => 'hotel', 'telefono' => '+34 971 374 512'],

            // ========== BINIBECA ==========
            ['localizacion' => 'Binibeca', 'nombre' => 'Apartamentos Binibeca', 'tipo' => 'hostal', 'telefono' => '+34 971 377 422'],

            // ========== ES CASTELL ==========
            ['localizacion' => 'Es Castell', 'nombre' => 'Hotel del Lago', 'tipo' => 'hotel', 'telefono' => '+34 971 363 750'],
        ];

        $contador = 0;
        foreach ($alojamientos as $item) {
            $loc = $localizaciones[$item['localizacion']] ?? null;
            if ($loc) {
                Alojamiento::create([
                    'localizacion_id' => $loc->id,
                    'nombre' => $item['nombre'],
                    'tipo' => $item['tipo'],
                    'telefono' => $item['telefono'],
                    'activo' => true,
                ]);
                $contador++;
            } else {
                $this->command->warn("Localización no encontrada: {$item['localizacion']}");
            }
        }

        $this->command->info("Se añadieron $contador alojamientos al GR-223 (Camí de Cavalls).");
    }
}
