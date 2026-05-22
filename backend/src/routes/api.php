<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\LocalizacionController;
use App\Http\Controllers\Api\AlojamientoController;
use App\Http\Controllers\Api\PlanificacionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestController;

// Autenticación pública
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Planificación de itinerarios automática (pública)
Route::get('/rutas/planificar', [RutaController::class, 'planificar']);
Route::get('/test', [TestController::class, 'test']);

// Lectura pública de recursos de senderismo
Route::get('/rutas', [RutaController::class, 'index']);
Route::get('/rutas/{id}', [RutaController::class, 'show']);

Route::get('/localizaciones', [LocalizacionController::class, 'index']);
Route::get('/localizaciones/{id}', [LocalizacionController::class, 'show']);

Route::get('/alojamientos', [AlojamientoController::class, 'index']);
Route::get('/alojamientos/{id}', [AlojamientoController::class, 'show']);

// Rutas protegidas (requieren token de portador Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Gestión de planificaciones del usuario
    Route::apiResource('planificaciones', PlanificacionController::class)->only(['index', 'show', 'store', 'destroy']);

    // Retorno de datos de sesión activa
    Route::get('/usuario', function (Request $request) {
        $usuario = $request->user();
        return response()->json([
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'nick' => $usuario->nick,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
            'avatar_url' => $usuario->avatar ? asset('storage/' . $usuario->avatar) : null,
        ]);
    });

    // Actualizar perfil
    Route::post('/usuario/update', [AuthController::class, 'updatePerfil']);

    // Escritura de recursos globales (Restringido en Frontend/Backend para rol 'admin')
    Route::post('/rutas', [RutaController::class, 'store']);
    Route::put('/rutas/{id}', [RutaController::class, 'update']);
    Route::delete('/rutas/{id}', [RutaController::class, 'destroy']);

    Route::post('/localizaciones', [LocalizacionController::class, 'store']);
    Route::put('/localizaciones/{id}', [LocalizacionController::class, 'update']);
    Route::delete('/localizaciones/{id}', [LocalizacionController::class, 'destroy']);

    Route::post('/alojamientos', [AlojamientoController::class, 'store']);
    Route::put('/alojamientos/{id}', [AlojamientoController::class, 'update']);
    Route::delete('/alojamientos/{id}', [AlojamientoController::class, 'destroy']);
});
