<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComentarioAlojamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioAlojamientoController extends Controller
{
    /**
     * Guardar un nuevo comentario para un alojamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'alojamiento_id' => 'required|exists:alojamientos,id',
            'texto' => 'required|string|max:1000',
        ]);

        // Guardamos el comentario inyectando directamente el ID del usuario autenticado
        $comentario = ComentarioAlojamiento::create([
            'usuario_id' => Auth::id(),
            'alojamiento_id' => $request->alojamiento_id,
            'texto' => $request->texto,
            'activo' => true // Activo por defecto
        ]);

        // Lo volvemos a cargar con la relación del usuario (para traer su nick en caliente)
        $comentario->load('usuario');

        return response()->json([
            'status' => 'success',
            'message' => 'Comentario publicado con éxito',
            'data' => $comentario
        ], 201);
    }

    /**
     * Desactivar (borrado lógico) un comentario si el usuario es el propietario o admin.
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

        $comentario->update(['activo' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'El comentario ha sido retirado de la plataforma.'
        ]);
    }
}