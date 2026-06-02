<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class GR11AlojamientosSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = Ruta::where('nombre', 'GR-11 · Senda Pirenaica')->first();

        if (!$ruta) {
            $this->command->error('Primero ejecuta GR11Seeder');
            return;
        }

        $localizaciones = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        $alojamientos = [
            // ========== CATALUÑA ==========
            ['localizacion' => 'Cap de Creus', 'nombre' => 'Hostal Mediterráneo', 'tipo' => 'hostal', 'telefono' => '+34 972 387 441'],
            ['localizacion' => 'Port de la Selva', 'nombre' => 'Hotel Port de la Selva', 'tipo' => 'hotel', 'telefono' => '+34 972 387 228'],
            ['localizacion' => 'Llançà', 'nombre' => 'Hotel Grifeu', 'tipo' => 'hotel', 'telefono' => '+34 972 380 120'],
            ['localizacion' => 'La Jonquera', 'nombre' => 'Hotel Plaza Inn', 'tipo' => 'hotel', 'telefono' => '+34 972 555 051'],
            ['localizacion' => 'Albanyà', 'nombre' => 'Can Xiquet', 'tipo' => 'casa_rural', 'telefono' => '+34 972 544 075'],
            ['localizacion' => 'Beget', 'nombre' => 'Cal Sastre', 'tipo' => 'casa_rural', 'telefono' => '+34 972 544 031'],
            ['localizacion' => 'Camprodon', 'nombre' => 'Hotel Edelweiss', 'tipo' => 'hotel', 'telefono' => '+34 972 740 054'],
            ['localizacion' => 'Setcases', 'nombre' => 'Hotel Bonansa', 'tipo' => 'hotel', 'telefono' => '+34 972 136 109'],
            ['localizacion' => 'Refugi Ulldeter', 'nombre' => 'Refugi Ulldeter', 'tipo' => 'albergue', 'telefono' => '+34 972 136 110', 'plazas' => 60],
            ['localizacion' => 'Planoles', 'nombre' => 'Cal Mariner', 'tipo' => 'casa_rural', 'telefono' => '+34 972 729 014'],
            ['localizacion' => 'Ribes de Freser', 'nombre' => 'Hotel Alpino', 'tipo' => 'hotel', 'telefono' => '+34 972 720 159'],
            ['localizacion' => 'Queralbs', 'nombre' => 'Hostal Can Ventura', 'tipo' => 'hostal', 'telefono' => '+34 972 720 163'],
            ['localizacion' => 'Refugi Malniu', 'nombre' => 'Refugi Malniu', 'tipo' => 'albergue', 'telefono' => '+34 973 332 029', 'plazas' => 40],
            ['localizacion' => 'Meranges', 'nombre' => 'Hotel Cal Francés', 'tipo' => 'hotel', 'telefono' => '+34 972 779 416'],
            ['localizacion' => 'Lles de Cerdanya', 'nombre' => 'Hotel Lles', 'tipo' => 'hotel', 'telefono' => '+34 973 357 011'],

            // ========== ANDORRA ==========
            ['localizacion' => 'El Pas de la Casa', 'nombre' => 'Hotel El Pas', 'tipo' => 'hotel', 'telefono' => '+376 750 450'],
            ['localizacion' => 'Encamp', 'nombre' => 'Hotel Encamp', 'tipo' => 'hotel', 'telefono' => '+376 832 222'],
            ['localizacion' => 'Canillo', 'nombre' => 'Hotel Roc Meler', 'tipo' => 'hotel', 'telefono' => '+376 851 000'],
            ['localizacion' => 'Refugi de Comapedrosa', 'nombre' => 'Refugi Comapedrosa', 'tipo' => 'albergue', 'telefono' => '+376 743 360', 'plazas' => 50],
            ['localizacion' => 'Arans', 'nombre' => 'Hotel Arans', 'tipo' => 'hotel', 'telefono' => '+376 737 530'],

            // ========== ARAGÓN ==========
            ['localizacion' => 'Refugi de Certascan', 'nombre' => 'Refugi Certascan', 'tipo' => 'albergue', 'telefono' => '+34 973 625 057', 'plazas' => 40],
            ['localizacion' => 'Areu', 'nombre' => 'Hotel Aigüestortes', 'tipo' => 'hotel', 'telefono' => '+34 973 620 151'],
            ['localizacion' => 'Espot', 'nombre' => 'Hotel Roca', 'tipo' => 'hotel', 'telefono' => '+34 973 620 115'],
            ['localizacion' => 'Refugi Ernest Mallafré', 'nombre' => 'Refugi Mallafré', 'tipo' => 'albergue', 'telefono' => '+34 973 625 087', 'plazas' => 50],
            ['localizacion' => 'Refugi Colomina', 'nombre' => 'Refugi Colomina', 'tipo' => 'albergue', 'telefono' => '+34 973 625 057', 'plazas' => 40],
            ['localizacion' => 'Refugi de la Restanca', 'nombre' => 'Refugi Restanca', 'tipo' => 'albergue', 'telefono' => '+34 973 625 037', 'plazas' => 60],
            ['localizacion' => 'Refugi Cap de Llauset', 'nombre' => 'Refugi Cap de Llauset', 'tipo' => 'albergue', 'telefono' => '+34 974 243 332', 'plazas' => 40],
            ['localizacion' => 'Refugi de Viadós', 'nombre' => 'Refugi Viadós', 'tipo' => 'albergue', 'telefono' => '+34 974 243 314', 'plazas' => 50],
            ['localizacion' => 'Parque Nacional de Ordesa', 'nombre' => 'Hotel Ordesa', 'tipo' => 'hotel', 'telefono' => '+34 974 343 123'],
            ['localizacion' => 'Refugi de Pineta', 'nombre' => 'Refugi Pineta', 'tipo' => 'albergue', 'telefono' => '+34 974 343 346', 'plazas' => 60],
            ['localizacion' => 'Refugio de Bujaruelo', 'nombre' => 'Refugio Bujaruelo', 'tipo' => 'albergue', 'telefono' => '+34 974 348 233', 'plazas' => 50],
            ['localizacion' => 'Refugio de Bachimaña', 'nombre' => 'Refugio Bachimaña', 'tipo' => 'albergue', 'telefono' => '+34 974 348 234', 'plazas' => 40],
            ['localizacion' => 'Refugio de Respomuso', 'nombre' => 'Refugio Respomuso', 'tipo' => 'albergue', 'telefono' => '+34 974 348 235', 'plazas' => 50],
            ['localizacion' => 'Sallent de Gállego', 'nombre' => 'Hotel Mirador de Sallent', 'tipo' => 'hotel', 'telefono' => '+34 974 488 200'],
            ['localizacion' => 'Candanchú', 'nombre' => 'Hotel Candanchú', 'tipo' => 'hotel', 'telefono' => '+34 974 373 102'],
            ['localizacion' => 'Refugio de Lizara', 'nombre' => 'Refugio Lizara', 'tipo' => 'albergue', 'telefono' => '+34 974 373 008', 'plazas' => 60],

            // ========== NAVARRA / PAÍS VASCO ==========
            ['localizacion' => 'Isaba', 'nombre' => 'Hotel Palacio de Aralar', 'tipo' => 'hotel', 'telefono' => '+34 948 893 020'],
            ['localizacion' => 'Ochagavía', 'nombre' => 'Hotel Lola', 'tipo' => 'hotel', 'telefono' => '+34 948 890 622'],
            ['localizacion' => 'Refugio de Belagua', 'nombre' => 'Refugio Belagua', 'tipo' => 'albergue', 'telefono' => '+34 948 890 262', 'plazas' => 50],
            ['localizacion' => 'Roncesvalles', 'nombre' => 'Colegiata de Roncesvalles', 'tipo' => 'albergue', 'telefono' => '+34 948 760 000', 'plazas' => 183],
            ['localizacion' => 'Roncesvalles', 'nombre' => 'Hostal Roncesvalles', 'tipo' => 'hostal', 'telefono' => '+34 948 760 225'],
            ['localizacion' => 'Elizondo', 'nombre' => 'Hotel Elizondo', 'tipo' => 'hotel', 'telefono' => '+34 948 583 050'],
            ['localizacion' => 'Zugarramurdi', 'nombre' => 'Hotel Zugarramurdi', 'tipo' => 'hotel', 'telefono' => '+34 948 599 061'],
            ['localizacion' => 'Hondarribia', 'nombre' => 'Hotel Río Bidasoa', 'tipo' => 'hotel', 'telefono' => '+34 943 641 303'],
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

        $this->command->info("Se añadieron $contador alojamientos al GR-11.");
    }
}
