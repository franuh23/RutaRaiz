<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RutaController;

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

// Rutas solo admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/rutas/create', [RutaController::class, 'create'])->name('rutas.create');
    Route::post('/rutas', [RutaController::class, 'store'])->name('rutas.store');
    Route::get('/rutas/{ruta}/edit', [RutaController::class, 'edit'])->name('rutas.edit');
    Route::put('/rutas/{ruta}', [RutaController::class, 'update'])->name('rutas.update');
    Route::delete('/rutas/{ruta}', [RutaController::class, 'destroy'])->name('rutas.destroy');
});

Route::get('/rutas', [RutaController::class, 'index'])->name('rutas.index');
Route::get('/rutas/{ruta}', [RutaController::class, 'show'])->name('rutas.show');
