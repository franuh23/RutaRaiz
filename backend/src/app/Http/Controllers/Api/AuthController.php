<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // Registro de usuarios públicos
    public function register(Request $request)
    {
        $request->validate([
            'nick' => 'required|string|max:255|unique:usuarios',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario = Usuario::create([
            'nick' => $request->nick,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'usuario',
            'activo' => true, // Por defecto entran activos
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Inicio de sesión Blindado contra Baneos 🔒
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Comprobamos si las credenciales de email y contraseña coinciden
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Buscamos el registro real del usuario en la base de datos de Neon
        $usuario = Usuario::where('email', $request->email)->firstOrFail();

        // EL CERROJO: Si el admin lo marcó como inactivo/baneado, frenamos el login
        if (!$usuario->activo) {
            return response()->json([
                'message' => 'Tu cuenta en RutaRaíz ha sido suspendida temporal o permanentemente por los administradores.'
            ], 403); // Devolvemos un código 403 Forbidden (Acceso prohibido)
        }

        // 4. Si está activo, permitimos la generación de token normal
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    // Actualizar perfil
    public function updatePerfil(Request $request)
    {
        $usuario = $request->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:usuarios,nick,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|string', // Validamos como string porque viaja en Base64
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->nick = $request->nick;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        // Si viene un nuevo Base64 en la petición, lo machacamos directo en Neon
        if ($request->filled('avatar')) {
            $usuario->avatar = $request->avatar;
        }

        $usuario->save();

        return response()->json([
            'message' => 'Perfil actualizado con éxito en Neon',
            'user' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'nick' => $usuario->nick,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'avatar_url' => $usuario->avatar, // Devolvemos el Base64 tal cual. ¡React lo pinta directo!
            ]
        ]);
    }
}
