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
     * Calcula las etapas de una ruta según los parámetros del usuario
     */
    public function planificar(PlanificarRequest $request)
    {
        // Cargamos las localizaciones con sus respectivos alojamientos
        $ruta = Ruta::with('localizaciones.alojamientos')->findOrFail($request->ruta_id);

        // El .values() es clave para que los índices sean 0, 1, 2... sin saltos
        $localizaciones = $ruta->localizaciones->sortBy('distancia_desde_inicio')->values();

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

        $i = $indiceInicio;

        // 👣 BUCLE INTELIGENTE: Avanzamos buscando la mejor parada para cada jornada
        while ($i < $indiceFin) {
            $inicioEtapa = $localizaciones[$i];
            $mejorDestinoIndice = $i + 1;
            $menorDiferencia = null;

            // Buscamos cuál de las siguientes paradas se ajusta mejor a los km deseados
            for ($j = $i + 1; $j <= $indiceFin; $j++) {
                // 🚀 CORREGIDO: Usamos 'distancia_desde_inicio' en ambas partes
                $distanciaTotalEtapa = $localizaciones[$j]->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;

                $diferencia = abs($distanciaTotalEtapa - $kmDia);
                $limiteMaximoPasarse = $kmDia + 6; // Permitimos pasarnos un máximo de 6km si el pueblo lo vale

                if ($menorDiferencia === null || $diferencia < $menorDiferencia) {
                    if ($distanciaTotalEtapa <= $limiteMaximoPasarse || $j == $i + 1) {
                        $menorDiferencia = $diferencia;
                        $mejorDestinoIndice = $j;
                    }
                }

                // Si ya nos estamos pasando demasiado del objetivo, dejamos de evaluar este día
                if ($distanciaTotalEtapa > $kmDia + 10) {
                    break;
                }
            }

            // Asignamos el destino óptimo calculado para la jornada
            $destinoEtapa = $localizaciones[$mejorDestinoIndice];
            $distanciaFinalEtapa = $destinoEtapa->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;

            $etapas[] = [
                'dia' => $dia,
                'inicio' => $inicioEtapa->nombre,
                'fin' => $destinoEtapa->nombre,
                'distancia' => round($distanciaFinalEtapa, 1),
                'alojamientos' => $destinoEtapa->alojamientos ?? []
            ];

            $dia++;
            $i = $mejorDestinoIndice; // Mañana salimos desde donde dormimos hoy
        }

        return response()->json([
            'etapas' => $etapas,
            'km_dia' => $kmDia,
            'total_km' => round($localizaciones[$indiceFin]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio, 1),
            'dias_totales' => count($etapas)
        ]);
    }
}
