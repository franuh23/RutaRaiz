<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alojamiento;
use App\Http\Resources\AlojamientoResource;
use App\Http\Requests\AlojamientoPost;
use App\Http\Requests\AlojamientoPut;
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
    public function store(AlojamientoPost $request) // 🔌 Conectado Form Request oficial
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Creamos usando estrictamente el array validado de campos reales
        $alojamiento = Alojamiento::create($request->validated());

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
    public function update(AlojamientoPut $request, string $id) // 🔌 Conectado Form Request oficial
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $alojamiento = Alojamiento::findOrFail($id);

        $alojamiento->update($request->validated());

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
