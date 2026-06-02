<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\LocalizacionController;
use App\Http\Controllers\Api\AlojamientoController;
use App\Http\Controllers\Api\ComentarioAlojamientoController;
use App\Http\Controllers\Api\PlanificacionController;
use App\Http\Controllers\Api\ComunidadController;
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
    Route::put('/planificaciones/{id}', [PlanificacionController::class, 'update']);
    Route::get('/planificaciones/{id}/pdf', [PlanificacionController::class, 'exportarPdf']);
    Route::get('/planificaciones/{id}/excel', [PlanificacionController::class, 'exportarExcel']);

    // Rutas de la Red Social de la Comunidad (Protegidas)
    Route::get('/comunidad/planificaciones', [ComunidadController::class, 'index']);
    Route::post('/comunidad/planificaciones/{id}/like', [ComunidadController::class, 'toggleLike']);
    Route::post('/comunidad/planificaciones/{id}/clonar', [ComunidadController::class, 'clonar']);

    // Retorno de datos de sesión activa (Corregido para Base64)
    Route::get('/usuario', function (Request $request) {
        $usuario = $request->user();
        return response()->json([
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'nick' => $usuario->nick,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
            'avatar_url' => $usuario->avatar ? $usuario->avatar : null,
        ]);
    });

    // Actualizar perfil
    Route::post('/usuario/update', [AuthController::class, 'updatePerfil']);

    // Comentarios
    Route::post('/comentarios-alojamiento', [ComentarioAlojamientoController::class, 'store']);
    Route::delete('/comentarios-alojamiento/{id}', [ComentarioAlojamientoController::class, 'destroy']);

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

    // Gestión de Usuarios Globales (Admin API)
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);

    // Seguimiento y Progreso del itinerario en tiempo real
    Route::post('/planificaciones/{id}/empezar', [PlanificacionController::class, 'empezarRuta']);
    Route::put('/planificaciones/{planificacionId}/etapas/{etapaId}/toggle', [PlanificacionController::class, 'toggleEtapa']);
    Route::post('/planificaciones/{id}/parar', [PlanificacionController::class, 'pararRuta']);
    Route::post('/planificaciones/{id}/finalizar', [PlanificacionController::class, 'finalizarRuta']);
});
