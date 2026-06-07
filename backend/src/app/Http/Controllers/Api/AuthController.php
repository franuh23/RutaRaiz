<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nick' => 'required|string|max:255|unique:usuarios',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
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

        // Crear el usuario
        $usuario = Usuario::create([
            'nick' => $request->nick,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'usuario',
            'activo' => true,
        ]);

        // Generar token de acceso
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Authenticate a user and generate an access token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verificar credenciales
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Obtener el usuario
        $usuario = Usuario::where('email', $request->email)->firstOrFail();

        // Verificar si la cuenta está activa
        if (!$usuario->activo) {
            return response()->json([
                'message' => 'Tu cuenta en RutaRaíz ha sido suspendida temporal o permanentemente por los administradores.'
            ], 403);
        }

        // Generar token de acceso
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request)
    {
        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updatePerfil(Request $request)
    {
        $usuario = $request->user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:usuarios,nick,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'avatar' => 'nullable|string',
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

        // Actualizar datos básicos
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->nick = $request->nick;
        $usuario->email = $request->email;

        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }

        // Actualizar avatar si se proporciona
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
