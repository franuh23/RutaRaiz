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
        // Validación según el tipo de planificación
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'localizacion_inicio_id' => 'required|exists:localizaciones,id',
            'fecha_inicio' => 'required|date',
            'tipo_planificacion' => 'required|in:destino_ritmo,dias_ritmo,destino_dias',
            'dias_disponibles' => 'required_if:tipo_planificacion,dias_ritmo,destino_dias|nullable|integer|min:1|max:90',
            'km_dia' => 'required_if:tipo_planificacion,destino_ritmo,dias_ritmo|nullable|numeric|min:1|max:100',
            'localizacion_fin_id' => 'required_if:tipo_planificacion,destino_dias|nullable|exists:localizaciones,id',
        ]);

        // Cargar la ruta y ordenar sus localizaciones por distancia real
        $ruta = Ruta::with('localizaciones')->findOrFail($request->ruta_id);
        $localizaciones = $ruta->localizaciones->sortBy('distancia_desde_inicio')->values();
        $inicioId = $request->localizacion_inicio_id;
        $finId = $request->localizacion_fin_id;
        $tipo = $request->tipo_planificacion;

        // Buscar el índice de inicio
        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        if ($indiceInicio === false) {
            return response()->json(['error' => 'Localizaciones no válidas'], 422);
        }

        // Determinar el índice de fin según el tipo de planificación
        if ($tipo === 'dias_ritmo' || (empty($finId) && $tipo === 'destino_ritmo')) {
            $indiceFin = $localizaciones->count() - 1;
        } else {
            $indiceFin = $localizaciones->search(fn($loc) => $loc->id == $finId);
            if ($indiceFin === false || $indiceInicio >= $indiceFin) {
                return response()->json(['error' => 'La localización de fin debe ser posterior a la de inicio.'], 422);
            }
        }

        // Calcular variables según el tipo de planificación
        $kmDia = $request->km_dia;
        $diasMaximos = $request->dias_disponibles;

        if ($tipo === 'destino_dias') {
            $distanciaTotalTramo = $localizaciones[$indiceFin]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio;
            $kmDia = $diasMaximos > 0 ? round($distanciaTotalTramo / $diasMaximos, 2) : 20;
        }

        // Generar etapas
        $etapasCalculadas = [];
        $dia = 1;
        $i = $indiceInicio;
        $indiceDetencionReal = $indiceFin;

        while ($i < $indiceFin) {

            // Límite de días en modo días_ritmo o destino_dias
            if (($tipo === 'dias_ritmo' || $tipo === 'destino_dias') && $dia > $diasMaximos) {
                $indiceDetencionReal = $i;
                break;
            }

            $inicioEtapa = $localizaciones[$i];
            $mejorDestinoIndice = $i + 1;
            $menorDiferencia = null;

            // Recalcular km_día en modo destino_dias para distribuir equitativamente
            if ($tipo === 'destino_dias') {
                $distanciaRestante = $localizaciones[$indiceFin]->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;
                $diasRestantes = ($diasMaximos - $dia) + 1;
                $kmDia = $diasRestantes > 0 ? ($distanciaRestante / $diasRestantes) : $distanciaRestante;
            }

            // Buscar la mejor localización para finalizar la etapa
            for ($j = $i + 1; $j <= $indiceFin; $j++) {
                $distanciaTotalEtapa = $localizaciones[$j]->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;
                $diferencia = abs($distanciaTotalEtapa - $kmDia);
                $limiteMaximoPasarse = ($tipo === 'destino_dias' && $dia == $diasMaximos) ? $kmDia + 30 : $kmDia + 6;

                if ($menorDiferencia === null || $diferencia < $menorDiferencia) {
                    if ($distanciaTotalEtapa <= $limiteMaximoPasarse || $j == $i + 1) {
                        $menorDiferencia = $diferencia;
                        $mejorDestinoIndice = $j;
                    }
                }

                if ($distanciaTotalEtapa > $kmDia + 15) {
                    break;
                }
            }

            $distanciaFinalEtapa = $localizaciones[$mejorDestinoIndice]->distancia_desde_inicio - $localizaciones[$i]->distancia_desde_inicio;

            $etapasCalculadas[] = [
                'dia' => $dia,
                'localizacion_inicio_id' => $localizaciones[$i]->id,
                'localizacion_fin_id' => $localizaciones[$mejorDestinoIndice]->id,
                'distancia' => round($distanciaFinalEtapa, 2),
            ];

            $dia++;
            $i = $mejorDestinoIndice;
        }

        // Guardar la planificación
        $finalRealId = ($tipo === 'dias_ritmo') ? $localizaciones[$indiceDetencionReal]->id : $localizaciones[$indiceFin]->id;

        $planificacion = Planificacion::create([
            'usuario_id' => Auth::id(),
            'ruta_id' => $request->ruta_id,
            'localizacion_inicio_id' => $request->localizacion_inicio_id,
            'localizacion_fin_id' => $finalRealId,
            'fecha_inicio' => $request->fecha_inicio,
            'km_dia' => round($kmDia, 2), // Guardamos el ritmo final equilibrado
            'dias_totales' => count($etapasCalculadas),
            'activo' => true,
        ]);

        // Guardar las etapas
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
            'etapas' => function ($query) {
                $query->orderBy('dia', 'asc');
            },
            'etapas.localizacionInicio',
            'etapas.localizacionFin.alojamientos'
        ])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        // Formatear etapas manualmente
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
        })->values()->all();

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

        // No permitir publicar planificaciones clonadas
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

    /**
     * Export planification as PDF.
     */
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

    /**
     * Export planification as Excel.
     */
    public function exportarExcel(string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);
        $nombreArchivo = 'Itinerario_' . str_replace(' ', '_', $planificacion->ruta->nombre) . '.xlsx';
        return Excel::download(new PlanificacionExport($planificacion->id), $nombreArchivo);
    }

    /**
     * Start route tracking.
     */
    public function empezarRuta(string $id)
    {
        $usuarioId = Auth::id();
        // Desactivar cualquier otra ruta en curso
        Planificacion::where('usuario_id', $usuarioId)->update(['en_curso' => false]);

        $planificacion = Planificacion::where('usuario_id', $usuarioId)->findOrFail($id);
        $planificacion->update(['en_curso' => true]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Buen Camino! Has iniciado el seguimiento de esta ruta.'
        ]);
    }

    /**
     * Stop route tracking.
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
     * Toggle stage completion status.
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
            'completada' => (bool) $etapa->completada,
            'message' => $etapa->completada ? '¡Etapa completada!' : 'Etapa marcada como pendiente.'
        ]);
    }

    /**
     * Complete and archive the route.
     */
    public function finalizarRuta(string $id)
    {
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);

        $planificacion->update([
            'en_curso' => false,
            'activo' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Enhorabuena, peregrino! Has completado tu itinerario y archivado la ruta en tu historial.'
        ]);
    }
}
