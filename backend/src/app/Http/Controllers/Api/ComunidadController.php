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
     * LISTAR PLANIFICACIONES PÚBLICAS
     * Devuelve todas las planificaciones compartidas por la comunidad con el conteo de likes
     */
    public function index()
    {
        // Conectamos las relaciones reales para poder sacar los nombres de los pueblos
        $publicas = Planificacion::with(['ruta', 'usuario', 'etapas.localizacionInicio', 'etapas.localizacionFin'])
            ->withCount('likes')
            ->where('is_public', true)
            ->latest()
            ->get();

        $usuarioId = Auth::id();

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
                // 🚀 MAPEADO REAL: Sacamos los nombres de la relación de la BD
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
     * ALTERNAR LIKE (Toggle Like)
     * Si no tiene like se lo da, si ya lo tiene se lo quita
     */
    public function toggleLike(string $id)
    {
        // Comprobamos que la planificación existe y es pública
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
     * BOTÓN CLONAR PLANIFICACIÓN ("Añadir a mi mochila")
     * Duplica la ruta de otro usuario y la guarda en las tuyas privadas
     */
    public function clonar(string $id)
    {
        // Buscamos la planificación pública con todas sus etapas asociadas
        $original = Planificacion::with('etapas')->where('is_public', true)->findOrFail($id);
        $usuarioId = Auth::id();

        // Evitamos que te clones a ti mismo por lógica de interfaz
        if ($original->usuario_id === $usuarioId) {
            return response()->json(['error' => 'No puedes clonar tu propia planificación.'], 400);
        }

        // ⚡ TRUCO REPLICATE DE LARAVEL: Duplica el objeto en memoria conservando las propiedades
        $clon = $original->replicate();
        $clon->usuario_id = $usuarioId; // Cambiamos el dueño al usuario logueado
        $clon->is_public = false; // Nace siendo una copia privada del usuario
        $clon->original_id = $original->id;
        $clon->save();

        // Duplicamos en cascada cada una de las etapas originales asociándolas al nuevo clon
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
