@extends('components.layout')

@section('title', 'Planificación')
@section('content')
    <h1>Planificación</h1>

    <p><strong>Ruta:</strong> {{ $planificacion->ruta->nombre }}</p>
    <p><strong>Inicio:</strong> {{ $planificacion->localizacionInicio->nombre }}
        ({{ $planificacion->localizacionInicio->distancia_desde_inicio }} km)</p>
    @if ($planificacion->localizacionFin)
        <p><strong>Fin:</strong> {{ $planificacion->localizacionFin->nombre }}
            ({{ $planificacion->localizacionFin->distancia_desde_inicio }} km)</p>
    @else
        <p><strong>Fin:</strong> No definido (hasta el final de la ruta)</p>
    @endif
    <p><strong>Fecha inicio:</strong> {{ $planificacion->fecha_inicio }}</p>
    <p><strong>Km por día:</strong> {{ $planificacion->km_dia }}</p>

    <h2>Etapas generadas</h2>

    @if ($planificacion->etapas->count())
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
                @foreach ($planificacion->etapas as $etapa)
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

    <hr>

    <h3>Comentarios</h3>

    @if ($planificacion->comentarios->count())
        @foreach ($planificacion->comentarios as $comentario)
            <div style="border-bottom:1px solid #ccc; margin-bottom:10px; padding-bottom:10px;">
                <strong>{{ $comentario->usuario->nick ?? 'Usuario' }}</strong>
                <small>{{ $comentario->created_at->format('d/m/Y H:i') }}</small>
                <p>{{ $comentario->texto }}</p>
            </div>
        @endforeach
    @else
        <p>Sin comentarios aún.</p>
    @endif

    @auth
        <h4>Deja tu comentario</h4>
        <form action="{{ route('planificaciones.comentarios.store', $planificacion) }}" method="POST">
            @csrf
            <textarea name="texto" rows="3" style="width:100%;" placeholder="Escribe tu comentario..." required></textarea>
            <button type="submit">Enviar comentario</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Inicia sesión</a> para dejar un comentario.</p>
    @endauth

    <a href="{{ route('planificaciones.edit', $planificacion) }}">Editar</a>
    <a href="{{ route('planificaciones.index') }}">Volver</a>
@endsection
