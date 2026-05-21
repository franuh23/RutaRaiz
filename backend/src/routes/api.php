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

// Planificación pública
Route::get('/rutas/planificar', [RutaController::class, 'planificar']);
Route::get('/test', [TestController::class, 'test']);

// Rutas públicas
Route::apiResource('rutas', RutaController::class)->only(['index', 'show']);
Route::apiResource('localizaciones', LocalizacionController::class)->only(['index', 'show']);
Route::apiResource('alojamientos', AlojamientoController::class)->only(['index', 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('planificaciones', PlanificacionController::class)->only(['index', 'show', 'store', 'destroy']);

    Route::get('/usuario', function (Request $request) {
        return $request->user();
    });
});
