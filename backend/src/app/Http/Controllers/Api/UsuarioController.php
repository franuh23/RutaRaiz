<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * List all users in the system.
     */
    public function index()
    {
        // Verificar permisos de administrador
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Obtener todos los usuarios ordenados por ID
        $usuarios = Usuario::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $usuarios
        ]);
    }

    /**
     * Update a user's role or active status.
     */
    public function update(Request $request, string $id)
    {
        // Verificar permisos de administrador
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = Usuario::findOrFail($id);

        // Evitar que el administrador se modifique a sí mismo
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
     * Permanently delete a user.
     */
    public function destroy(string $id)
    {
        // Verificar permisos de administrador
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $usuario = Usuario::findOrFail($id);

        // Evitar que el administrador se elimine a sí mismo
        if (Auth::user()->id == $usuario->id) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta de administrador.'], 400);
        }

        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado de la base de datos de RutaRaíz'
        ]);
    }
}
