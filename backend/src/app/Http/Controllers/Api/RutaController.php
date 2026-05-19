<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Http\Resources\RutaResource;
use App\Http\Requests\PlanificarRequest;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rutas = Ruta::with(['localizaciones', 'comentarios.usuario'])->get();
        return RutaResource::collection($rutas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ruta = Ruta::with(['localizaciones.alojamientos', 'comentarios.usuario'])->findOrFail($id);
        return new RutaResource($ruta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
    * Calcula las etapas de una ruta según los parámetros del usuario.
    */
    public function planificar(PlanificarRequest $request)
    {
        $ruta = Ruta::with('localizaciones')->findOrFail($request->ruta_id);
        $localizaciones = $ruta->localizaciones->sortBy('distancia_desde_inicio');

        $inicioId = $request->localizacion_inicio_id;
        $finId = $request->localizacion_fin_id;

        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        $indiceFin = $finId ? $localizaciones->search(fn($loc) => $loc->id == $finId) : $localizaciones->count() - 1;

        if ($indiceInicio === false || $indiceFin === false || $indiceInicio >= $indiceFin) {
            return response()->json(['error' => 'Localizaciones no válidas'], 422);
        }

        $kmDia = $request->km_dia;
        $etapas = [];
        $dia = 1;
        $kmAcumuladosDia = 0;
        $inicioEtapa = $localizaciones[$indiceInicio];

        for ($i = $indiceInicio + 1; $i <= $indiceFin; $i++) {
            $distanciaTramo = $localizaciones[$i]->distancia_desde_inicio - $localizaciones[$i-1]->distancia_desde_inicio;

            if ($kmAcumuladosDia + $distanciaTramo > $kmDia && $kmAcumuladosDia > 0) {
                $etapas[] = [
                    'dia' => $dia,
                    'inicio' => $inicioEtapa->nombre,
                    'fin' => $localizaciones[$i-1]->nombre,
                    'distancia' => round($kmAcumuladosDia, 1),
                ];
                $dia++;
                $inicioEtapa = $localizaciones[$i-1];
                $kmAcumuladosDia = $distanciaTramo;
            } else {
                $kmAcumuladosDia += $distanciaTramo;
            }
        }

        if ($kmAcumuladosDia > 0) {
            $etapas[] = [
                'dia' => $dia,
                'inicio' => $inicioEtapa->nombre,
                'fin' => $localizaciones[$indiceFin]->nombre,
                'distancia' => round($kmAcumuladosDia, 1),
            ];
        }

        return response()->json([
            'etapas' => $etapas,
            'km_dia' => $kmDia,
            'total_km' => round($localizaciones[$indiceFin]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio, 1),
            'dias_totales' => count($etapas)
        ]);
    }
}
