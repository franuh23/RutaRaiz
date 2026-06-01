<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Models\Planificacion;
use App\Exports\PlanificacionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\PlanificacionResource;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $etapasCalculadas = [];
        $dia = 1;
        $kmAcumulados = 0;
        $inicioEtapa = $localizaciones[$indiceInicio];

        for ($i = $indiceInicio + 1; $i <= $indiceFin; $i++) {
            $distanciaTramo = $localizaciones[$i]->distancia_desde_inicio - $localizaciones[$i - 1]->distancia_desde_inicio;

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

        if ($kmAcumulados > 0) {
            $etapasCalculadas[] = [
                'dia' => $dia,
                'localizacion_inicio_id' => $inicioEtapa->id,
                'localizacion_fin_id' => $localizaciones[$indiceFin]->id,
                'distancia' => round($kmAcumulados, 2),
            ];
        }

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

        foreach ($etapasCalculadas as $etapa) {
            $planificacion->etapas()->create($etapa);
        }

        $planificacion->load(['ruta', 'etapas.localizacionInicio', 'etapas.localizacionFin']);
        return new PlanificacionResource($planificacion);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $planificacion = Planificacion::with([
            'ruta',
            'etapas' => function($query) {
                $query->orderBy('dia', 'asc'); // 🚀 REPARADO: Fijamos el orden SQL para que no se muevan de sitio
            },
            'etapas.localizacionInicio',
            'etapas.localizacionFin.alojamientos'
        ])
        ->where('usuario_id', Auth::id())
        ->findOrFail($id);

        $etapasFormateadas = $planificacion->etapas->map(function ($etapa) {
            return [
                'id' => $etapa->id,
                'dia' => $etapa->dia,
                'inicio' => $etapa->localizacionInicio->nombre ?? 'Inicio',
                'fin' => $etapa->localizacionFin->nombre ?? 'Fin',
                'distancia' => round($etapa->distancia, 1),
                'completada' => (bool) $etapa->completada,
                'alojamientos' => $etapa->localizacionFin->alojamientos ?? []
            ];
        })->values()->all(); // Aseguramos colección limpia de índices

        return response()->json([
            'data' => [
                'id' => $planificacion->id,
                'fecha_inicio' => $planificacion->fecha_inicio,
                'km_dia' => $planificacion->km_dia,
                'ruta_nombre' => $planificacion->ruta->nombre ?? 'Camino',
                'total_km' => round($planificacion->etapas->sum('distancia'), 1),
                'dias_totales' => $planificacion->dias_totales,
                'en_curso' => (bool) $planificacion->en_curso,
                'etapas' => $etapasFormateadas
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);

        $request->validate([
            'is_public' => 'required|boolean'
        ]);

        if ($request->is_public && $planificacion->original_id !== null) {
            return response()->json([
                'status' => 'error',
                'message' => 'No puedes publicar en la comunidad una planificación que has clonado de otro peregrino.'
            ], 403);
        }

        $planificacion->update([
            'is_public' => $request->is_public
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $planificacion->is_public ? '¡Planificación publicada en la comunidad!' : 'Planificación retirada de la comunidad.',
            'is_public' => $planificacion->is_public
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);
        $planificacion->etapas()->delete();
        $planificacion->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Planificación y sus etapas eliminadas correctamente.'
        ], 200);
    }

    public function exportarPdf(string $id)
    {
        $planificacion = Planificacion::with(['ruta', 'etapas.localizacionInicio', 'etapas.localizacionFin', 'usuario'])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        $totalKm = $planificacion->etapas->sum('distancia');
        $fechaMochilera = \Carbon\Carbon::parse($planificacion->fecha_inicio)->format('d/m/Y');

        $data = [
            'planificacion' => $planificacion,
            'totalKm' => round($totalKm, 1),
            'fecha' => $fechaMochilera
        ];

        $pdf = Pdf::loadView('pdf.itinerario', $data)->setPaper('a4', 'portrait');
        $nombreArchivo = 'Itinerario_' . str_replace(' ', '_', $planificacion->ruta->nombre) . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    public function exportarExcel(string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);
        $nombreArchivo = 'Itinerario_' . str_replace(' ', '_', $planificacion->ruta->nombre) . '.xlsx';
        return Excel::download(new PlanificacionExport($planificacion->id), $nombreArchivo);
    }

    /**
     * INICIAR SEGUIMIENTO DE RUTA
     */
    public function empezarRuta(string $id)
    {
        $usuarioId = Auth::id();
        Planificacion::where('usuario_id', $usuarioId)->update(['en_curso' => false]);

        $planificacion = Planificacion::where('usuario_id', $usuarioId)->findOrFail($id);
        $planificacion->update(['en_curso' => true]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Buen Camino! Has iniciado el seguimiento de esta ruta.'
        ]);
    }

    /**
     * 🚀 NUEVO: DETENER SEGUIMIENTO DE RUTA (Para poder pararla cuando quieras)
     */
    public function pararRuta(string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);
        $planificacion->update(['en_curso' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Seguimiento detenido correctamente.'
        ]);
    }

    /**
     * MARCAR / DESMARCAR ETAPA (Toggle Progreso)
     */
    public function toggleEtapa(string $planificacionId, string $etapaId)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($planificacionId);
        $etapa = $planificacion->etapas()->findOrFail($etapaId);

        $etapa->update([
            'completada' => !$etapa->completada
        ]);

        return response()->json([
            'status' => 'success',
            'completada' => (bool)$etapa->completada,
            'message' => $etapa->completada ? '¡Etapa completada!' : 'Etapa marcada como pendiente.'
        ]);
    }
}
