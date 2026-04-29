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
        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('rutas/imagenes', 'public');

            // solo para debug
            $archivo = $request->file('imagen');
            dump($archivo->getRealPath());
            dump(Storage::path($imagenPath));
        }

        Ruta::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dificultad' => $request->dificultad,
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'kilometros' => $request->kilometros,
            'imagen' => $imagenPath,
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
        $data = $request->except(['imagen']);

        // Solo si se sube nueva imagen
        if ($request->hasFile('imagen')) {

            // Borrar imagen anterior si existe
            if ($ruta->imagen && Storage::disk('public')->exists($ruta->imagen)) {
                Storage::disk('public')->delete($ruta->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('rutas/imagenes', 'public');
        }

        $ruta->update($data);
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
