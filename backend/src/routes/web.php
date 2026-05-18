<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\LocalizacionController;
use App\Http\Controllers\AlojamientoController;
use App\Http\Controllers\PlanificacionController;

// Rutas de autenticación (login/logout)
Route::get('/login', function () {
    return view('usuarios.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas.',
    ]);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas para usuarios logueados (planificaciones)
Route::middleware(['auth'])->group(function () {
    Route::get('/planificaciones', [PlanificacionController::class, 'index'])->name('planificaciones.index');
    Route::get('/planificaciones/create', [PlanificacionController::class, 'create'])->name('planificaciones.create');
    Route::post('/planificaciones', [PlanificacionController::class, 'store'])->name('planificaciones.store');
    Route::get('/planificaciones/{planificacion}', [PlanificacionController::class, 'show'])->name('planificaciones.show');
    Route::get('/planificaciones/{planificacion}/edit', [PlanificacionController::class, 'edit'])->name('planificaciones.edit');
    Route::put('/planificaciones/{planificacion}', [PlanificacionController::class, 'update'])->name('planificaciones.update');
    Route::delete('/planificaciones/{planificacion}', [PlanificacionController::class, 'destroy'])->name('planificaciones.destroy');
});

// Rutas solo admin
Route::middleware(['auth', 'admin'])->group(function () {

    // Rutas de rutas
    Route::get('/rutas/create', [RutaController::class, 'create'])->name('rutas.create');
    Route::post('/rutas', [RutaController::class, 'store'])->name('rutas.store');
    Route::get('/rutas/{ruta}/edit', [RutaController::class, 'edit'])->name('rutas.edit');
    Route::put('/rutas/{ruta}', [RutaController::class, 'update'])->name('rutas.update');
    Route::delete('/rutas/{ruta}', [RutaController::class, 'destroy'])->name('rutas.destroy');

    // Rutas de localizaciones
    Route::get('/localizaciones/create', [LocalizacionController::class, 'create'])->name('localizaciones.create');
    Route::post('/localizaciones', [LocalizacionController::class, 'store'])->name('localizaciones.store');
    Route::get('/localizaciones/{localizacion}/edit', [LocalizacionController::class, 'edit'])->name('localizaciones.edit');
    Route::put('/localizaciones/{localizacion}', [LocalizacionController::class, 'update'])->name('localizaciones.update');
    Route::delete('/localizaciones/{localizacion}', [LocalizacionController::class, 'destroy'])->name('localizaciones.destroy');

    // Rutas de alojamientos
    Route::get('/alojamientos/create', [AlojamientoController::class, 'create'])->name('alojamientos.create');
    Route::post('/alojamientos', [AlojamientoController::class, 'store'])->name('alojamientos.store');
    Route::get('/alojamientos/{alojamiento}/edit', [AlojamientoController::class, 'edit'])->name('alojamientos.edit');
    Route::put('/alojamientos/{alojamiento}', [AlojamientoController::class, 'update'])->name('alojamientos.update');
    Route::delete('/alojamientos/{alojamiento}', [AlojamientoController::class, 'destroy'])->name('alojamientos.destroy');

});

// *** Rutas públicas con parámetros ***

// Rutas
Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::get('/rutas/{ruta}', [RutaController::class, 'show'])->name('rutas.show');

// Localizaciones
Route::get('/localizaciones', [LocalizacionController::class, 'index'])->name('localizaciones.index');
Route::get('/localizaciones/{localizacion}', [LocalizacionController::class, 'show'])->name('localizaciones.show');

// Alojamientos
Route::get('/alojamientos', [AlojamientoController::class, 'index'])->name('alojamientos.index');
Route::get('/alojamientos/{alojamiento}', [AlojamientoController::class, 'show'])->name('alojamientos.show');

// Comentarios en rutas
Route::post('/rutas/{ruta}/comentarios', [RutaController::class, 'storeComentario'])->name('rutas.comentarios.store');

// Comentarios en localizaciones
Route::post('/localizaciones/{localizacion}/comentarios', [LocalizacionController::class, 'storeComentario'])->name('localizaciones.comentarios.store');

// Comentarios en alojamientos
Route::post('/alojamientos/{alojamiento}/comentarios', [AlojamientoController::class, 'storeComentario'])->name('alojamientos.comentarios.store');

// Comentarios en planificaciones
Route::post('/planificaciones/{planificacion}/comentarios', [PlanificacionController::class, 'storeComentario'])->name('planificaciones.comentarios.store');

// *** Rutas públicas con parámetros ***
