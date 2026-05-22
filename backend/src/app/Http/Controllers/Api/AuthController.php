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
            'activo' => true,
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $usuario = Usuario::where('email', $request->email)->firstOrFail();
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->nick = $request->nick;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($usuario->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($usuario->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $usuario->avatar = $path;
        }

        $usuario->save();

        return response()->json([
            'message' => 'Perfil actualizado con éxito',
            'user' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'nick' => $usuario->nick,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'avatar_url' => $usuario->avatar ? asset('storage/' . $usuario->avatar) : null,
            ]
        ]);
    }
}
