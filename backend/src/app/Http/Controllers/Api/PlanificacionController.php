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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cargamos la planificación directamente con las etapas guardadas en la BD
        // y sus respectivas localizaciones de inicio y fin (Eager Loading)
        $planificacion = Planificacion::with([
            'ruta',
            'etapas.localizacionInicio.alojamientos',
            'etapas.localizacionFin.alojamientos'
        ])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        // Formateamos las etapas leyendo directamente de las tablas de la BD
        $etapasFormateadas = $planificacion->etapas->map(function ($etapa) {
            return [
                'dia' => $etapa->dia,
                'inicio' => $etapa->localizacionInicio->nombre,
                'fin' => $etapa->localizacionFin->nombre,
                'distancia' => round($etapa->distancia, 1),
                // Agrupamos los alojamientos disponibles en el punto de destino de la etapa
                'alojamientos' => $etapa->localizacionFin->alojamientos ?? []
            ];
        });

        // Calculamos los kilómetros totales sumando lo que se guardó físicamente en las etapas
        $totalKm = $planificacion->etapas->sum('distancia');

        return response()->json([
            'data' => [
                'id' => $planificacion->id,
                'fecha_inicio' => $planificacion->fecha_inicio,
                'km_dia' => $planificacion->km_dia,
                'ruta_nombre' => $planificacion->ruta->nombre,
                'total_km' => round($totalKm, 1),
                'dias_totales' => $planificacion->dias_totales,
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

    /**
     * Summary of exportarPdf
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function exportarPdf(string $id)
    {
        // Buscamos la planificación garantizando que sea del usuario logueado
        $planificacion = Planificacion::with([
            'ruta',
            'etapas.localizacionInicio',
            'etapas.localizacionFin',
            'usuario'
        ])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);

        // Calculamos los datos generales
        $totalKm = $planificacion->etapas->sum('distancia');

        $fechaMochilera = \Carbon\Carbon::parse($planificacion->fecha_inicio)->format('d/m/Y');

        $data = [
            'planificacion' => $planificacion,
            'totalKm' => round($totalKm, 1),
            'fecha' => $fechaMochilera
        ];

        // Generamos el PDF y la descarga
        $pdf = Pdf::loadView('pdf.itinerario', $data)->setPaper('a4', 'portrait');
        $nombreArchivo = 'Itinerario_' . str_replace(' ', '_', $planificacion->ruta->nombre) . '.pdf';
        return $pdf->download($nombreArchivo);
    }

    /**
     * Summary of exportarExcel
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarExcel(string $id)
    {
        // Aseguramos que la planificación existe y es del usuario logueado
        $planificacion = Planificacion::where('usuario_id', Auth::id())->findOrFail($id);

        $nombreArchivo = 'Itinerario_' . str_replace(' ', '_', $planificacion->ruta->nombre) . '.xlsx';

        return Excel::download(new PlanificacionExport($planificacion->id), $nombreArchivo);
    }
}
