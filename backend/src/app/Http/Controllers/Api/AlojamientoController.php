<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alojamiento;
use App\Http\Resources\AlojamientoResource;
use Illuminate\Http\Request;

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
        //
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
