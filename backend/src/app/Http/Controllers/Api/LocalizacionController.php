<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Localizacion;
use App\Http\Resources\LocalizacionResource;
use App\Http\Requests\LocalizacionPost;
use App\Http\Requests\LocalizacionPut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalizacionController extends Controller
{
    /**
     * Display a listing of localizations.
     */
    public function index()
    {
        $localizaciones = Localizacion::with(['alojamientos', 'comentarios.usuario'])->get();
        return LocalizacionResource::collection($localizaciones);
    }

    /**
     * Store a newly created localization.
     */
    public function store(LocalizacionPost $request)
    {
        // Verificar permisos de administrador
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Crear la localización con los datos validados
        $localizacion = Localizacion::create($request->validated());

        return response()->json([
            'message' => 'Localización creada correctamente',
            'data' => new LocalizacionResource($localizacion)
        ], 201);
    }

    /**
     * Display the specified localization.
     */
    public function show(string $id)
    {
        $localizacion = Localizacion::with(['alojamientos', 'comentarios.usuario'])->findOrFail($id);
        return new LocalizacionResource($localizacion);
    }

    /**
     * Update the specified localization.
     */
    public function update(LocalizacionPut $request, string $id)
    {
        // Verificar permisos de administrador
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $localizacion = Localizacion::findOrFail($id);

        $localizacion->update($request->validated());

        return response()->json([
            'message' => 'Localización actualizada correctamente',
            'data' => new LocalizacionResource($localizacion)
        ]);
    }

    /**
     * Remove the specified localization.
     */
    public function destroy(string $id)
    {
        // Verificar permisos de administrador
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
