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
    public function index()
    {
        $localizaciones = Localizacion::with(['alojamientos', 'comentarios.usuario'])->get();
        return LocalizacionResource::collection($localizaciones);
    }

    public function store(LocalizacionPost $request) // 🔌 Usamos el validador oficial
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Se crea usando solo los datos validados (ruta_id, nombre, distancia_desde_inicio, distancia_desde_fin, descripcion)
        $localizacion = Localizacion::create($request->validated());

        return response()->json([
            'message' => 'Localización creada correctamente',
            'data' => new LocalizacionResource($localizacion)
        ], 201);
    }

    public function show(string $id)
    {
        $localizacion = Localizacion::with(['alojamientos', 'comentarios.usuario'])->findOrFail($id);
        return new LocalizacionResource($localizacion);
    }

    public function update(LocalizacionPut $request, string $id) // 🔌 Usamos el validador oficial
    {
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
