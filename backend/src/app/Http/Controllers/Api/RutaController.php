<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Http\Resources\RutaResource;
use App\Http\Requests\PlanificarRequest;
use App\Http\Requests\RutaPost;
use App\Http\Requests\RutaPut;
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
     * Calcula las etapas de una ruta según los parámetros del usuario (Simulador al vuelo)
     */
    public function planificar(PlanificarRequest $request)
    {
        // Cargamos las localizaciones con sus respectivos alojamientos
        $ruta = Ruta::with('localizaciones.alojamientos')->findOrFail($request->ruta_id);
        $localizaciones = $ruta->localizaciones->sortBy('distancia_desde_inicio')->values();

        $inicioId = $request->localizacion_inicio_id;
        $finId = $request->localizacion_fin_id;
        $tipo = $request->tipo_planificacion;

        // Buscar el índice físico de salida
        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        if ($indiceInicio === false) {
            return response()->json(['error' => 'Localización de inicio no válida'], 422);
        }

        // 🚀 REPARADO: Control estricto de fin según el modo y si viene vacío
        if ($tipo === 'dias_ritmo' || (empty($finId) && $tipo === 'destino_ritmo')) {
            $indiceFin = $localizaciones->count() - 1; // Último hito de la ruta por defecto
        } else {
            $indiceFin = $localizaciones->search(fn($loc) => $loc->id == $finId);
            if ($indiceFin === false || $indiceInicio >= $indiceFin) {
                return response()->json(['error' => 'La localización de fin debe ser posterior a la de inicio.'], 422);
            }
        }

        // 🧮 PRE-CÁLCULO MATEMÁTICO DE RITMO (Variante 2: Reto Crono)
        $kmDia = $request->km_dia;
        $diasMaximos = $request->dias_disponibles;

        if ($tipo === 'destino_dias') {
            $distanciaTotalTramo = $localizaciones[$indiceFin]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio;
            $kmDia = $diasMaximos > 0 ? round($distanciaTotalTramo / $diasMaximos, 2) : 20;
        }

        $etapas = [];
        $dia = 1;
        $i = $indiceInicio;
        $indiceDetencionReal = $indiceFin;

        // 👣 BUCLE INTELIGENTE CON REPARTO EQUITATIVO DINÁMICO
        while ($i < $indiceFin) {

            // 🛑 FRENO DE MANO DE JORNADAS
            if (($tipo === 'dias_ritmo' || $tipo === 'destino_dias') && $dia > $diasMaximos) {
                $indiceDetencionReal = $i;
                break;
            }

            $inicioEtapa = $localizaciones[$i];
            $mejorDestinoIndice = $i + 1;
            $menorDiferencia = null;

            // 🚀 RECALCULO DE OBJETIVO DÍA A DÍA (Para evitar el efecto bola de nieve en Reto Crono)
            if ($tipo === 'destino_dias') {
                $distanciaRestante = $localizaciones[$indiceFin]->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;
                $diasRestantes = ($diasMaximos - $dia) + 1;
                $kmDia = $diasRestantes > 0 ? ($distanciaRestante / $diasRestantes) : $distanciaRestante;
            }

            // Buscamos cuál de las siguientes paradas se ajusta mejor al objetivo actual
            for ($j = $i + 1; $j <= $indiceFin; $j++) {
                $distanciaTotalEtapa = $localizaciones[$j]->distancia_desde_inicio - $inicioEtapa->distancia_desde_inicio;

                $diferencia = abs($distanciaTotalEtapa - $kmDia);

                // Margen flexible: si es el último día, permitimos llegar al final
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
            $i = $mejorDestinoIndice;
        }

        // Si salimos por freno de mano, recalculamos el índice geográfico real donde murió la estimación
        $finalRealIndice = ($tipo === 'dias_ritmo' || $tipo === 'destino_dias') ? $indiceDetencionReal : $indiceFin;

        return response()->json([
            'etapas' => $etapas,
            'km_dia' => round($kmDia, 1), // Mandamos el ritmo real (o el calculado) para las tarjetas de React
            'total_km' => round($localizaciones[$finalRealIndice]->distancia_desde_inicio - $localizaciones[$indiceInicio]->distancia_desde_inicio, 1),
            'dias_totales' => count($etapas)
        ]);
    }
}
