<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planificacion;
use App\Models\PlanificacionLike;
use App\Http\Resources\PlanificacionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComunidadController extends Controller
{
    /**
     * List all public planifications with like counts.
     */
    public function index()
    {
        // Cargar relaciones necesarias para obtener los nombres de localizaciones
        $publicas = Planificacion::with(['ruta', 'usuario', 'etapas.localizacionInicio', 'etapas.localizacionFin'])
            ->withCount('likes')
            ->where('is_public', true)
            ->latest()
            ->get();

        $usuarioId = Auth::id();

        // Mapear los datos manualmente para incluir información personalizada
        $data = $publicas->map(function ($p) use ($usuarioId) {
            return [
                'id' => $p->id,
                'usuario_id' => $p->usuario_id,
                'usuario_nick' => $p->usuario?->nick ?? 'Peregrino',
                'ruta_nombre' => $p->ruta?->nombre ?? 'Camino de Santiago',
                'fecha_inicio' => $p->fecha_inicio ? $p->fecha_inicio->format('Y-m-d') : null,
                'km_dia' => $p->km_dia,
                'dias_totales' => $p->dias_totales,
                'likes_count' => $p->likes_count,
                'ha_dado_like' => \App\Models\PlanificacionLike::where('usuario_id', $usuarioId)
                    ->where('planificacion_id', $p->id)
                    ->exists(),
                // Mapear etapas con sus localizaciones y alojamientos
                'etapas' => $p->etapas->map(function ($e) {
                    return [
                        'dia' => $e->dia,
                        'inicio' => $e->localizacionInicio?->nombre,
                        'fin' => $e->localizacionFin?->nombre,
                        'distancia' => round($e->distancia, 1),
                        'alojamientos' => $e->localizacionFin?->alojamientos ?? []
                    ];
                })
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Toggle like on a public planification.
     */
    public function toggleLike(string $id)
    {
        // Verificar que la planificación existe y es pública
        $planificacion = Planificacion::where('is_public', true)->findOrFail($id);
        $usuarioId = Auth::id();

        $likeExistente = PlanificacionLike::where('usuario_id', $usuarioId)
            ->where('planificacion_id', $planificacion->id)
            ->first();

        if ($likeExistente) {
            $likeExistente->delete();
            $meGusta = false;
        } else {
            PlanificacionLike::create([
                'usuario_id' => $usuarioId,
                'planificacion_id' => $planificacion->id
            ]);
            $meGusta = true;
        }

        return response()->json([
            'status' => 'success',
            'ha_dado_like' => $meGusta,
            'likes_count' => PlanificacionLike::where('planificacion_id', $planificacion->id)->count()
        ]);
    }

    /**
     * Clone a public planification to the authenticated user's collection.
     */
    public function clonar(string $id)
    {
        // Buscar la planificación pública con sus etapas
        $original = Planificacion::with('etapas')->where('is_public', true)->findOrFail($id);
        $usuarioId = Auth::id();

        // Evitar clonar la propia planificación
        if ($original->usuario_id === $usuarioId) {
            return response()->json(['error' => 'No puedes clonar tu propia planificación.'], 400);
        }

        // Duplicar la planificación
        $clon = $original->replicate();
        $clon->usuario_id = $usuarioId;
        $clon->is_public = false;
        $clon->original_id = $original->id;
        $clon->save();

        // Duplicar las etapas asociadas
        foreach ($original->etapas as $etapaOriginal) {
            $nuevaEtapa = $etapaOriginal->replicate();
            $nuevaEtapa->planificacion_id = $clon->id;
            $nuevaEtapa->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => '¡Ruta añadida con éxito a tu mochila de planificaciones!',
            'id' => $clon->id
        ], 201);
    }
}
