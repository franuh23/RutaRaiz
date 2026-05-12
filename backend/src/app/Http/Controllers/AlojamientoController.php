<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use App\Models\Localizacion;
use App\Http\Requests\AlojamientoPost;
use App\Http\Requests\AlojamientoPut;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AlojamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alojamientos = Alojamiento::with('localizacion')->paginate(20);
        return view('alojamientos.index', compact('alojamientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $localizaciones = Localizacion::where('activo', true)->get();
        return view('alojamientos.create', compact('localizaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlojamientoPost $request)
    {
        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('alojamientos', 'public');
        }

        Alojamiento::create([
            'localizacion_id' => $request->localizacion_id,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'tipo' => $request->tipo,
            'enlace' => $request->enlace,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'imagen' => $imagenPath,
            'activo' => $request->activo ?? true,
        ]);

        return redirect()->route('alojamientos.index')->with('success', 'Alojamiento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alojamiento $alojamiento)
    {
        $alojamiento->load((['localizacion', 'comentarios']));
        return view('alojamientos.show', compact('alojamiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alojamiento $alojamiento)
    {
        $localizaciones = Localizacion::where('activo', true)->get();
        return view('alojamientos.edit', compact('alojamiento', 'localizaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlojamientoPut $request, Alojamiento $alojamiento)
    {
        $data = $request->except(['imagen']);

        if ($request->hasFile('imagen')) {
            if ($alojamiento->imagen && Storage::disk('public')->exists($alojamiento->imagen)) {
                Storage::disk('public')->delete($alojamiento->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('alojamientos', 'public');
        }

        $alojamiento->update($data);

        return redirect()->route('alojamientos.show', $alojamiento)->with('success', 'Alojamiento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alojamiento $alojamiento)
    {
        if ($alojamiento->imagen && Storage::disk('public')->exists($alojamiento->imagen)) {
            Storage::disk('public')->delete($alojamiento->imagen);
        }
        $alojamiento->delete();

        return redirect()->route('alojamientos.index')->with('success', 'Alojamiento eliminado correctamente.');
    }
}
