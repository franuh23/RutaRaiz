<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alojamiento;
use App\Http\Resources\AlojamientoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlojamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alojamientos = Alojamiento::with(['comentarios.usuario'])->get();
        return AlojamientoResource::collection($alojamientos);
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
            'localizacion_id' => 'required|exists:localizaciones,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|max:100',
            'precio_noche' => 'required|numeric|min:0',
            'plazas_totales' => 'required|integer|min:0',
            'imagen' => 'nullable|string',
            'contacto' => 'nullable|string',
        ]);

        $alojamiento = Alojamiento::create($validated);

        return response()->json([
            'message' => 'Alojamiento creado correctamente',
            'data' => new AlojamientoResource($alojamiento)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alojamiento = Alojamiento::with(['comentarios.usuario'])->findOrFail($id);
        return new AlojamientoResource($alojamiento);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $alojamiento = Alojamiento::findOrFail($id);

        $validated = $request->validate([
            'localizacion_id' => 'sometimes|required|exists:localizaciones,id',
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'tipo' => 'sometimes|required|string|max:100',
            'precio_noche' => 'sometimes|required|numeric|min:0',
            'plazas_totales' => 'sometimes|required|integer|min:0',
            'imagen' => 'nullable|string',
            'contacto' => 'nullable|string',
        ]);

        $alojamiento->update($validated);

        return response()->json([
            'message' => 'Alojamiento actualizado correctamente',
            'data' => new AlojamientoResource($alojamiento)
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

        $alojamiento = Alojamiento::findOrFail($id);
        $alojamiento->delete();

        return response()->json([
            'message' => 'Alojamiento eliminado correctamente'
        ]);
    }
}
