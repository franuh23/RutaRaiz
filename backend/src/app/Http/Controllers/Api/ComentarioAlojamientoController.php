<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComentarioAlojamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioAlojamientoController extends Controller
{
    /**
     * Store a new comment for an accommodation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'alojamiento_id' => 'required|exists:alojamientos,id',
            'texto' => 'required|string|max:1000',
        ]);

        // Guardar el comentario con el usuario autenticado
        $comentario = ComentarioAlojamiento::create([
            'usuario_id' => Auth::id(),
            'alojamiento_id' => $request->alojamiento_id,
            'texto' => $request->texto,
            'activo' => true
        ]);

        // Cargar la relación con el usuario para obtener su nick
        $comentario->load('usuario');

        return response()->json([
            'status' => 'success',
            'message' => 'Comentario publicado con éxito',
            'data' => $comentario
        ], 201);
    }

    /**
     * Soft delete a comment (owner or admin only).
     */
    public function destroy(string $id)
    {
        $comentario = ComentarioAlojamiento::findOrFail($id);
        $usuarioActivo = Auth::user();

        // Si no es el creador del comentario y no es administrador, no puede borrar
        if ($comentario->usuario_id !== $usuarioActivo->id && $usuarioActivo->rol !== 'admin') {
            return response()->json([
                'message' => 'No tienes permisos para eliminar este comentario.'
            ], 403);
        }

        // Desactivar el comentario
        $comentario->update(['activo' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'El comentario ha sido retirado de la plataforma.'
        ]);
    }
}
