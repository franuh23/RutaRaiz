<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Http\Resources\RutaResource;
use App\Http\Requests\PlanificarRequest;
use App\Http\Requests\RutaPost; // 🎒 Importamos tu Request real de creación
use App\Http\Requests\RutaPut;  // 📝 Importamos tu Request real de edición
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(RutaPost $request) // 🔌 Conectamos tu Form Request corregido
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Creamos la ruta usando directamente los datos limpios y validados (inicio, fin, kilometros, etc.)
        $ruta = Ruta::create($request->validated());

        return response()->json([
            'message' => 'Ruta creada correctamente',
            'data' => new RutaResource($ruta)
        ], 201);
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
    public function update(RutaPut $request, string $id) // 🔌 Conectamos tu Form Request corregido
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ruta = Ruta::findOrFail($id);

        // Actualizamos con las reglas adaptadas del PUT (donde los campos pueden ser opcionales al editar)
        $ruta->update($request->validated());

        return response()->json([
            'message' => 'Ruta actualizada correctamente',
            'data' => new RutaResource($ruta)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ruta = Ruta::findOrFail($id);
        $ruta->delete();

        return response()->json([
            'message' => 'Ruta eliminada correctamente'
        ]);
    }

    /**
     * Calcula las etapas de una ruta según los parámetros del usuario incluyendo alojamientos.
     */
    public function planificar(PlanificarRequest $request)
    {
        // Cargamos las localizaciones con sus respectivos alojamientos para tenerlos listos
        $ruta = Ruta::with('localizaciones.alojamientos')->findOrFail($request->ruta_id);
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
                $destinoEtapa = $localizaciones[$i-1];
                $etapas[] = [
                    'dia' => $dia,
                    'inicio' => $inicioEtapa->nombre,
                    'fin' => $destinoEtapa->nombre,
                    'distancia' => round($kmAcumuladosDia, 1),
                    'alojamientos' => $destinoEtapa->alojamientos ?? []
                ];
                $dia++;
                $inicioEtapa = $destinoEtapa;
                $kmAcumuladosDia = $distanciaTramo;
            } else {
                $kmAcumuladosDia += $distanciaTramo;
            }
        }

        if ($kmAcumuladosDia > 0) {
            $destinoFinal = $localizaciones[$indiceFin];
            $etapas[] = [
                'dia' => $dia,
                'inicio' => $inicioEtapa->nombre,
                'fin' => $destinoFinal->nombre,
                'distancia' => round($kmAcumuladosDia, 1),
                'alojamientos' => $destinoFinal->alojamientos ?? []
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
