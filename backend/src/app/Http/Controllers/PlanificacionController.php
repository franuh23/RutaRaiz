<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use App\Models\Ruta;
use App\Models\Localizacion;
use App\Http\Requests\PlanificacionPost;
use App\Http\Requests\PlanificacionPut;
use Illuminate\Support\Facades\Auth;

class PlanificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planificaciones = Planificacion::with(['ruta', 'usuario'])
            ->where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('planificaciones.index', compact('planificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rutas = Ruta::where('activo', true)->get();
        return view('planificaciones.create', compact('rutas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanificacionPost $request)
    {
        $data = $request->validated();
        $data['usuario_id'] = Auth::id();
        $data['activo'] = true;

        $planificacion = Planificacion::create($data);

        // Generar etapas
        $this->generarEtapas($planificacion);

        return redirect()->route('planificaciones.show', $planificacion)->with('success', 'Planificación creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Planificacion $planificacion)
    {
         if ($planificacion->usuario_id !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }
        $planificacion->load(['ruta', 'localizacionInicio', 'localizacionFin', 'etapas.localizacionInicio', 'etapas.localizacionFin']);
        return view('planificaciones.show', compact('planificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planificacion $planificacion)
    {
        if ($planificacion->usuario_id !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }
        $rutas = Ruta::where('activo', true)->get();
        return view('planificaciones.edit', compact('planificacion', 'rutas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanificacionPut $request, Planificacion $planificacion)
    {
        if ($planificacion->usuario_id !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $planificacion->update($request->validated());

        return redirect()->route('planificaciones.show', $planificacion)->with('success', 'Planificación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planificacion $planificacion)
    {
        if ($planificacion->usuario_id !== Auth::id() && Auth::user()->rol !== 'admin') {
            abort(403);
        }
        $planificacion->delete();
        return redirect()->route('planificaciones.index')->with('success', 'Planificación eliminada correctamente.');
    }

    // Método para generar las etapas en planificación
    private function generarEtapas(Planificacion $planificacion)
    {
        // Obtener localizaciones de la ruta ordenadas por distancia
        $localizaciones = $planificacion->ruta->localizaciones()
            ->orderBy('distancia_desde_inicio')
            ->get();

        // Encontrar índices de inicio y fin
        $inicioId = $planificacion->localizacion_inicio_id;
        $finId = $planificacion->localizacion_fin_id;

        $indiceInicio = $localizaciones->search(fn($loc) => $loc->id == $inicioId);
        $indiceFin = $finId ? $localizaciones->search(fn($loc) => $loc->id == $finId) : $localizaciones->count() - 1;

        if ($indiceInicio === false || $indiceFin === false || $indiceInicio >= $indiceFin) {
            return;
        }

        $kmDia = $planificacion->km_dia;
        $dia = 1;
        $inicioEtapa = $localizaciones[$indiceInicio];
        $kmAcumuladosDia = 0;
        $ultimoIndice = $indiceInicio;

        for ($i = $indiceInicio + 1; $i <= $indiceFin; $i++) {
            $localizacion = $localizaciones[$i];
            $distanciaTramo = $localizacion->distancia_desde_inicio - $localizaciones[$i - 1]->distancia_desde_inicio;

            if ($kmAcumuladosDia + $distanciaTramo > $kmDia && $kmAcumuladosDia > 0) {
                // Guardar etapa
                $planificacion->etapas()->create([
                    'dia' => $dia,
                    'localizacion_inicio_id' => $inicioEtapa->id,
                    'localizacion_fin_id' => $localizaciones[$i - 1]->id,
                    'distancia' => $kmAcumuladosDia,
                ]);

                // Reiniciar para nueva etapa
                $dia++;
                $inicioEtapa = $localizaciones[$i - 1];
                $kmAcumuladosDia = $distanciaTramo;
                $ultimoIndice = $i;
            } else {
                $kmAcumuladosDia += $distanciaTramo;
                $ultimoIndice = $i;
            }
        }

        // Última etapa
        if ($kmAcumuladosDia > 0 || $ultimoIndice == $indiceFin) {
            $planificacion->etapas()->create([
                'dia' => $dia,
                'localizacion_inicio_id' => $inicioEtapa->id,
                'localizacion_fin_id' => $localizaciones[$indiceFin]->id,
                'distancia' => $kmAcumuladosDia,
            ]);
        }
    }
}
