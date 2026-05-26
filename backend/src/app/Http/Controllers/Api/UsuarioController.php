<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios del sistema.
     */
    public function index()
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Devolvemos todos los usuarios ordenados por id
        $usuarios = Usuario::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $usuarios
        ]);
    }

    /**
     * Actualizar el rol o el estado activo de un usuario.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = Usuario::findOrFail($id);

        // Evitar que el propio admin se desactive o se quite el rol a sí mismo
        if (Auth::user()->id == $usuario->id) {
            return response()->json(['message' => 'No puedes modificar tus propios permisos de administrador.'], 400);
        }

        $validated = $request->validate([
            'rol' => 'sometimes|required|in:admin,usuario',
            'activo' => 'sometimes|required|boolean',
        ]);

        $usuario->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario
        ]);
    }

    /**
     * Eliminar un usuario permanentemente.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = Usuario::findOrFail($id);

        if (Auth::user()->id == $usuario->id) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta de administrador.'], 400);
        }

        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado de la base de datos de RutaRaíz'
        ]);
    }
}
