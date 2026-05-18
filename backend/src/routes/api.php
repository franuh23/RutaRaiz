<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\LocalizacionController;
use App\Http\Controllers\Api\AlojamientoController;
use App\Http\Controllers\Api\PlanificacionController;

// Rutas públicas
Route::apiResource('rutas', RutaController::class)->only(['index', 'show']);
Route::apiResource('localizaciones', LocalizacionController::class)->only(['index', 'show']);
Route::apiResource('alojamientos', AlojamientoController::class)->only(['index', 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('planificaciones', PlanificacionController::class)->only(['index', 'show']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
