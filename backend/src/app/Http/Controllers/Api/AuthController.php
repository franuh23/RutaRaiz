<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // 🚀 REGLA MODO 3.1 PRO DE SEGURIDAD

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

            // 🔒 CONTRASEÑA BLINDADA
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols()
            ],
        ], [
            'password.confirmed' => 'Las contraseñas introducidas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo especial.',
            'email.unique' => 'Este correo electrónico ya está registrado en RutaRaíz.',
            'nick.unique' => 'Este nick ya está siendo utilizado por otro usuario.',
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

    // Inicio de sesión Blindado contra Baneos 🔒
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

        if (!$usuario->activo) {
            return response()->json([
                'message' => 'Tu cuenta en RutaRaíz ha sido suspendida temporal o permanentemente por los administradores.'
            ], 403);
        }

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
            'avatar' => 'nullable|string',

            // 🔒 CONTRASEÑA EN PERFIL
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols()
            ],
        ], [
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La nueva contraseña debe contener al menos una letra.',
            'password.numbers' => 'La nueva contraseña debe contener al menos un número.',
            'password.symbols' => 'La nueva contraseña debe contener al menos un símbolo especial.',
            'nick.unique' => 'Este nick ya está registrado.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->nick = $request->nick;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

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
                'avatar_url' => $usuario->avatar,
            ]
        ]);
    }
}
