<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Localizacion;
use App\Http\Resources\LocalizacionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $localizaciones = Localizacion::with(['alojamientos', 'comentarios.usuario'])->get();
        return LocalizacionResource::collection($localizaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'distancia_desde_inicio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string',
        ]);

        $localizacion = Localizacion::create($validated);

        return response()->json([
            'message' => 'Localización creada correctamente',
            'data' => new LocalizacionResource($localizacion)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $localizacion = Localizacion::with(['alojamientos', 'comentarios.usuario'])->findOrFail($id);
        return new LocalizacionResource($localizacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $localizacion = Localizacion::findOrFail($id);

        $validated = $request->validate([
            'ruta_id' => 'sometimes|required|exists:rutas,id',
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'distancia_desde_inicio' => 'sometimes|required|numeric|min:0',
            'imagen' => 'nullable|string',
        ]);

        $localizacion->update($validated);

        return response()->json([
            'message' => 'Localización actualizada correctamente',
            'data' => new LocalizacionResource($localizacion)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $localizacion = Localizacion::findOrFail($id);
        $localizacion->delete();

        return response()->json([
            'message' => 'Localización eliminada correctamente'
        ]);
    }
}
