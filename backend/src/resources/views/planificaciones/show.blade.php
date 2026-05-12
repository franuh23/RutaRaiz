@extends('components.layout')

@section('title', 'Planificación')
@section('content')
    <h1>Planificación</h1>

    <p><strong>Ruta:</strong> {{ $planificacion->ruta->nombre }}</p>
    <p><strong>Inicio:</strong> {{ $planificacion->localizacionInicio->nombre }} ({{ $planificacion->localizacionInicio->distancia_desde_inicio }} km)</p>
    @if($planificacion->localizacionFin)
        <p><strong>Fin:</strong> {{ $planificacion->localizacionFin->nombre }} ({{ $planificacion->localizacionFin->distancia_desde_inicio }} km)</p>
    @else
        <p><strong>Fin:</strong> No definido (hasta el final de la ruta)</p>
    @endif
    <p><strong>Fecha inicio:</strong> {{ $planificacion->fecha_inicio }}</p>
    <p><strong>Km por día:</strong> {{ $planificacion->km_dia }}</p>

    <h2>Etapas generadas</h2>

    @if($planificacion->etapas->count())
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Distancia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planificacion->etapas as $etapa)
                <tr>
                    <td>{{ $etapa->dia }}</td>
                    <td>{{ $etapa->localizacionInicio->nombre }}</td>
                    <td>{{ $etapa->localizacionFin->nombre }}</td>
                    <td>{{ $etapa->distancia }} km</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se generaron etapas. Revisa que la ruta tenga localizaciones.</p>
    @endif

    <a href="{{ route('planificaciones.edit', $planificacion) }}">Editar</a>
    <a href="{{ route('planificaciones.index') }}">Volver</a>
@endsection
