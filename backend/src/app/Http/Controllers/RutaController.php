<?php

namespace App\Http\Controllers;

use App\Http\Requests\RutaPost;
use App\Http\Requests\RutaPut;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rutas = Ruta::with('localizaciones')->get();
        return view('rutas.index', compact('rutas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rutas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RutaPost $request)
    {
        Ruta::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dificultad' => $request->dificultad,
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'kilometros' => $request->kilometros,
            'imagen' => $request->imagen,
        ]);

        return redirect()->route('rutas.index')->with('success', 'Ruta creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruta $ruta)
    {
        $ruta->load(['localizaciones', 'comentarios']);
        return view('rutas.show', compact('ruta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruta $ruta)
    {
        return view('rutas.edit', compact('ruta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RutaPut $request, Ruta $ruta)
    {
        $ruta->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dificultad' => $request->dificultad,
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'kilometros' => $request->kilometros,
            'imagen' => $request->imagen ?? $ruta->imagen,
        ]);

        return redirect()->route('rutas.show', $ruta)->with('success', 'Ruta actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruta $ruta)
    {
        // Borrar imagen de la ruta
        if ($ruta->imagen && Storage::disk('public')->exists($ruta->imagen)) {
            Storage::disk('public')->delete($ruta->imagen);
        }

        $ruta->delete();
        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }
}
