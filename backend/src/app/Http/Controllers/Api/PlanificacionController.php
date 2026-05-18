<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planificacion;
use App\Http\Resources\PlanificacionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PlanificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planificaciones = Planificacion::with(['ruta', 'etapas', 'comentarios.usuario'])
            ->where('usuario_id', Auth::id())
            ->get();
        return PlanificacionResource::collection($planificaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $planificacion = Planificacion::with(['ruta', 'etapas.localizacionInicio', 'etapas.localizacionFin', 'comentarios.usuario'])
            ->where('usuario_id', Auth::id())
            ->findOrFail($id);
        return new PlanificacionResource($planificacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
