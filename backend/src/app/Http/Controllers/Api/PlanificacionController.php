<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Models\Planificacion;
use App\Http\Resources\PlanificacionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PlanificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planificaciones = Planificacion::with(['ruta', 'etapas', 'comentarios.usuario'])
            ->where('usuario_id', Auth::id())
            ->get();
        return PlanificacionResource::collection($planificaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'localizacion_inicio_id' => 'required|exists:localizaciones,id',
            'localizacion_fin_id' => 'nullable|exists:localizaciones,id',
            'fecha_inicio' => 'required|date',
            'km_dia' => 'required|numeric|min:1|max:100',
        ]);

        // Calcular etapas igual que en RutaController@planificar
        $ruta = Ruta::with('localizaciones')->findOrFail($request->ruta_id);
        $localizaciones = $ruta->localizaciones->sortBy('distancia_desde_inicio');

        $inicioId = $request->localizacion_inicio_id;
        $finId = $request->localizacion_fin_id;

        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        $indiceFin = $finId
            ? $localizaciones->search(fn($loc) => $loc->id == $finId)
            : $localizaciones->count() - 1;

        if ($indiceInicio === false || $indiceFin === false || $indiceInicio >= $indiceFin) {
            return response()->json(['error' => 'Localizaciones no válidas'], 422);
        }

        $kmDia = $request->km_dia;
        $etapasCalculadas = [];
        $dia = 1;
        $kmAcumulados = 0;
        $inicioEtapa = $localizaciones[$indiceInicio];

        for ($i = $indiceInicio + 1; $i <= $indiceFin; $i++) {
            $distanciaTramo = $localizaciones[$i]->distancia_desde_inicio
                - $localizaciones[$i - 1]->distancia_desde_inicio;

            if ($kmAcumulados + $distanciaTramo > $kmDia && $kmAcumulados > 0) {
                $etapasCalculadas[] = [
                    'dia' => $dia,
                    'localizacion_inicio_id' => $inicioEtapa->id,
                    'localizacion_fin_id' => $localizaciones[$i - 1]->id,
                    'distancia' => round($kmAcumulados, 2),
                ];
                $dia++;
                $inicioEtapa = $localizaciones[$i - 1];
                $kmAcumulados = $distanciaTramo;
            } else {
                $kmAcumulados += $distanciaTramo;
            }
        }

        // Última etapa
        if ($kmAcumulados > 0) {
            $etapasCalculadas[] = [
                'dia' => $dia,
                'localizacion_inicio_id' => $inicioEtapa->id,
                'localizacion_fin_id' => $localizaciones[$indiceFin]->id,
                'distancia' => round($kmAcumulados, 2),
            ];
        }

        // Guardar planificación
        $planificacion = Planificacion::create([
            'usuario_id' => Auth::id(),
            'ruta_id' => $request->ruta_id,
            'localizacion_inicio_id' => $request->localizacion_inicio_id,
            'localizacion_fin_id' => $request->localizacion_fin_id,
            'fecha_inicio' => $request->fecha_inicio,
            'km_dia' => $request->km_dia,
            'dias_totales' => count($etapasCalculadas),
            'activo' => true,
        ]);

        // Guardar etapas
        foreach ($etapasCalculadas as $etapa) {
            $planificacion->etapas()->create($etapa);
        }

        // Devolver con etapas cargadas
        $planificacion->load(['ruta', 'etapas.localizacionInicio', 'etapas.localizacionFin']);
        return new PlanificacionResource($planificacion);
    }

    /**
     * Display the specified resource
     */
    public function show(string $id)
    {
        // Cargamos la planificación con su ruta, localizaciones y los alojamientos de estas
        $planificacion = Planificacion::with(['ruta.localizaciones.alojamientos'])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        $localizaciones = $planificacion->ruta->localizaciones->sortBy('distancia_desde_inicio');

        $inicioId = $planificacion->localizacion_inicio_id;
        $finId = $planificacion->localizacion_fin_id;

        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        $indiceFin = $finId ? $localizaciones->search(fn($loc) => $loc->id == $finId) : $localizaciones->count() - 1;

        if ($indiceInicio === false || $indiceFin === false || $indiceInicio >= $indiceFin) {
            return response()->json(['error' => 'Datos de planificación corruptos o inválidos'], 422);
        }

        $kmDia = $planificacion->km_dia;
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
            'data' => [
                'id' => $planificacion->id,
                'fecha_inicio' => $planificacion->fecha_inicio,
                'km_dia' => $planificacion->km_dia,
                'ruta_nombre' => $planificacion->ruta->nombre,
                'total_km' => round($localizaciones[$indiceFin]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio, 1),
                'dias_totales' => count($etapas),
                'etapas' => $etapas
            ]
        ]);
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
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);

        $planificacion->delete();

        return response()->json(['message' => 'Planificación eliminada']);
    }
}
