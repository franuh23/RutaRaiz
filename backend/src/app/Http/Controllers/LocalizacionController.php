<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalizacionPost;
use App\Http\Requests\LocalizacionPut;
use App\Http\Requests\ComentarioLocalizacionPost;
use App\Models\Localizacion;
use App\Models\ComentarioLocalizacion;
use App\Models\Ruta;
use Illuminate\Http\Request;

class LocalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $localizaciones = Localizacion::with('ruta')->paginate(20);
        return view('localizaciones.index' , compact('localizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rutas = Ruta::where('activo', true)->get();
        return view('localizaciones.create', compact('rutas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocalizacionPost $request)
    {
        Localizacion::create($request->validated());
        return redirect()->route('localizaciones.index')->with('success', 'Localización creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Localizacion $localizacion)
    {
        $localizacion->load(['ruta', 'alojamientos']);
        return view('localizaciones.show', compact('localizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Localizacion $localizacion)
    {
        $rutas = Ruta::where('activo', true)->get();
        return view('localizaciones.edit', compact('localizacion', 'rutas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocalizacionPut $request, Localizacion $localizacion)
    {
        $localizacion->update($request->validated());
        return redirect()->route('localizaciones.show', $localizacion)->with('success', 'Localización actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Localizacion $localizacion)
    {
        $localizacion->delete();
        return redirect()->route('localizaciones.index')->with('success', 'Localización eliminada correctamente.');
    }

    /**
     * Store a new comment for a localization.
     */
    public function storeComentario(ComentarioLocalizacionPost $request, Localizacion $localizacion)
    {
        // Crear el comentario
        ComentarioLocalizacion::create([
            'usuario_id' => auth()->id(),
            'localizacion_id' => $localizacion->id,
            'texto' => $request->texto,
            'activo' => true,
        ]);

        // Redirigir de vuelta a la página de la localización
        return redirect()->route('localizaciones.show', $localizacion)->with('success', 'Comentario añadido.');
    }
}
