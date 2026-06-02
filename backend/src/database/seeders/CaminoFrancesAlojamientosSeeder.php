<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Models\Alojamiento;

class CaminoFrancesAlojamientosSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = Ruta::where('nombre', 'Camino Francés')->first();

        if (!$ruta) {
            $this->command->error('Primero ejecuta CaminoFrancesSeeder');
            return;
        }

        $localizaciones = Localizacion::where('ruta_id', $ruta->id)->get()->keyBy('nombre');

        $alojamientos = [
            // ========== NAVARRA ==========
            ['localizacion' => 'Roncesvalles', 'nombre' => 'Colegiata de Roncesvalles', 'tipo' => 'albergue', 'telefono' => '+34 948 760 000', 'plazas' => 183],
            ['localizacion' => 'Roncesvalles', 'nombre' => 'Hostal Roncesvalles', 'tipo' => 'hostal', 'telefono' => '+34 948 760 225'],
            ['localizacion' => 'Burguete', 'nombre' => 'Hostal Burguete', 'tipo' => 'hostal', 'telefono' => '+34 948 760 005'],
            ['localizacion' => 'Zubiri', 'nombre' => 'Albergue Zubiri', 'tipo' => 'albergue', 'telefono' => '+34 948 304 218'],
            ['localizacion' => 'Pamplona', 'nombre' => 'Albergue Jesús y María', 'tipo' => 'albergue', 'telefono' => '+34 948 222 644', 'plazas' => 114],
            ['localizacion' => 'Pamplona', 'nombre' => 'Hotel Maisonnave', 'tipo' => 'hotel', 'telefono' => '+34 948 222 600'],
            ['localizacion' => 'Pamplona', 'nombre' => 'Hostal Arriazu', 'tipo' => 'hostal', 'telefono' => '+34 948 228 028'],
            ['localizacion' => 'Puente la Reina', 'nombre' => 'Albergue Puente', 'tipo' => 'albergue', 'telefono' => '+34 948 341 001'],
            ['localizacion' => 'Puente la Reina', 'nombre' => 'Hotel Jakue', 'tipo' => 'hotel', 'telefono' => '+34 948 340 177'],
            ['localizacion' => 'Estella', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 948 550 854'],
            ['localizacion' => 'Estella', 'nombre' => 'Hotel Yerri', 'tipo' => 'hotel', 'telefono' => '+34 948 550 620'],

            // ========== LA RIOJA ==========
            ['localizacion' => 'Logroño', 'nombre' => 'Albergue Santiago El Real', 'tipo' => 'albergue', 'telefono' => '+34 941 242 624', 'plazas' => 80],
            ['localizacion' => 'Logroño', 'nombre' => 'Hotel Carlton Rioja', 'tipo' => 'hotel', 'telefono' => '+34 941 242 100'],
            ['localizacion' => 'Logroño', 'nombre' => 'Pensión La Redonda', 'tipo' => 'hostal', 'telefono' => '+34 941 230 624'],
            ['localizacion' => 'Nájera', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 941 360 416'],
            ['localizacion' => 'Santo Domingo de la Calzada', 'nombre' => 'Albergue Miguel', 'tipo' => 'albergue', 'telefono' => '+34 941 343 145'],
            ['localizacion' => 'Santo Domingo de la Calzada', 'nombre' => 'Parador de Santo Domingo', 'tipo' => 'hotel', 'telefono' => '+34 941 340 300'],

            // ========== BURGOS ==========
            ['localizacion' => 'Belorado', 'nombre' => 'Albergue Cuatro Cantones', 'tipo' => 'albergue', 'telefono' => '+34 947 580 411'],
            ['localizacion' => 'San Juan de Ortega', 'nombre' => 'Albergue San Juan', 'tipo' => 'albergue', 'telefono' => '+34 947 260 255'],
            ['localizacion' => 'Burgos', 'nombre' => 'Albergue Municipal', 'tipo' => 'albergue', 'telefono' => '+34 947 228 611', 'plazas' => 150],
            ['localizacion' => 'Burgos', 'nombre' => 'Hotel NH Collection Palacio de la Merced', 'tipo' => 'hotel', 'telefono' => '+34 947 223 800'],
            ['localizacion' => 'Burgos', 'nombre' => 'Hostal Cuéntame', 'tipo' => 'hostal', 'telefono' => '+34 947 267 870'],
            ['localizacion' => 'Castrojeriz', 'nombre' => 'Albergue Ultreia', 'tipo' => 'albergue', 'telefono' => '+34 947 160 154'],

            // ========== PALENCIA ==========
            ['localizacion' => 'Frómista', 'nombre' => 'Albergue Estrella del Camino', 'tipo' => 'albergue', 'telefono' => '+34 979 810 109'],
            ['localizacion' => 'Carrión de los Condes', 'nombre' => 'Albergue Espiritú Santo', 'tipo' => 'albergue', 'telefono' => '+34 979 880 864', 'plazas' => 70],
            ['localizacion' => 'Sahagún', 'nombre' => 'Albergue Cluny', 'tipo' => 'albergue', 'telefono' => '+34 987 782 229'],

            // ========== LEÓN ==========
            ['localizacion' => 'León', 'nombre' => 'Albergue San Francisco de Asís', 'tipo' => 'albergue', 'telefono' => '+34 987 234 620', 'plazas' => 120],
            ['localizacion' => 'León', 'nombre' => 'Hotel Real Colegiata San Isidoro', 'tipo' => 'hotel', 'telefono' => '+34 987 259 000'],
            ['localizacion' => 'León', 'nombre' => 'Hostal Albany', 'tipo' => 'hostal', 'telefono' => '+34 987 254 199'],
            ['localizacion' => 'Astorga', 'nombre' => 'Albergue Si Quieres', 'tipo' => 'albergue', 'telefono' => '+34 987 618 758'],
            ['localizacion' => 'Astorga', 'nombre' => 'Hotel Spa Ciudad de Astorga', 'tipo' => 'hotel', 'telefono' => '+34 987 617 371'],
            ['localizacion' => 'Ponferrada', 'nombre' => 'Albergue San Nicolás', 'tipo' => 'albergue', 'telefono' => '+34 987 411 213'],
            ['localizacion' => 'Ponferrada', 'nombre' => 'Hotel El Bierzo Plaza', 'tipo' => 'hotel', 'telefono' => '+34 987 423 049'],
            ['localizacion' => 'Villafranca del Bierzo', 'nombre' => 'Albergue Ave Fénix', 'tipo' => 'albergue', 'telefono' => '+34 987 542 818'],

            // ========== GALICIA (LUGO) ==========
            ['localizacion' => 'O Cebreiro', 'nombre' => 'Albergue O Cebreiro', 'tipo' => 'albergue', 'telefono' => '+34 982 367 108'],
            ['localizacion' => 'Triacastela', 'nombre' => 'Albergue Complexo Xacobeo', 'tipo' => 'albergue', 'telefono' => '+34 982 548 111'],
            ['localizacion' => 'Sarria', 'nombre' => 'Albergue Obradoiro', 'tipo' => 'albergue', 'telefono' => '+34 982 531 661', 'plazas' => 100],
            ['localizacion' => 'Sarria', 'nombre' => 'Hotel Alfonso IX', 'tipo' => 'hotel', 'telefono' => '+34 982 530 011'],
            ['localizacion' => 'Portomarín', 'nombre' => 'Albergue O Mirador', 'tipo' => 'albergue', 'telefono' => '+34 982 545 254'],
            ['localizacion' => 'Palas de Rei', 'nombre' => 'Albergue El Caminante', 'tipo' => 'albergue', 'telefono' => '+34 982 380 135'],
            ['localizacion' => 'Melide', 'nombre' => 'Albergue O Apalpador', 'tipo' => 'albergue', 'telefono' => '+34 981 507 168'],
            ['localizacion' => 'Melide', 'nombre' => 'Hotel Carlos', 'tipo' => 'hotel', 'telefono' => '+34 981 506 226'],
            ['localizacion' => 'Arzúa', 'nombre' => 'Albergue Don Quijote', 'tipo' => 'albergue', 'telefono' => '+34 981 507 920'],
            ['localizacion' => 'Arzúa', 'nombre' => 'Pensión Luis', 'tipo' => 'hostal', 'telefono' => '+34 981 500 125'],
            ['localizacion' => 'O Pedrouzo', 'nombre' => 'Albergue O Pedrouzo', 'tipo' => 'albergue', 'telefono' => '+34 981 511 178'],
            ['localizacion' => 'Monte do Gozo', 'nombre' => 'Albergue Monte do Gozo', 'tipo' => 'albergue', 'telefono' => '+34 981 558 958', 'plazas' => 400],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Albergue Seminario Menor', 'tipo' => 'albergue', 'telefono' => '+34 981 563 810', 'plazas' => 300],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Parador de los Reyes Católicos', 'tipo' => 'hotel', 'telefono' => '+34 981 582 200'],
            ['localizacion' => 'Santiago de Compostela', 'nombre' => 'Hostal de los Reyes Católicos', 'tipo' => 'hostal', 'telefono' => '+34 981 582 200'],
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
            }
        }

        $this->command->info("Se añadieron $contador alojamientos al Camino Francés.");
    }
}
