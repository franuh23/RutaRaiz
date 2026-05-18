@extends('components.layout')

@section('title', $localizacion->nombre)
@section('content')
    <h1>{{ $localizacion->nombre }}</h1>

    <p><strong>Ruta:</strong> {{ $localizacion->ruta->nombre }}</p>
    <p><strong>Distancia desde inicio:</strong> {{ $localizacion->distancia_desde_inicio }} km</p>
    <p><strong>Distancia hasta el final:</strong> {{ $localizacion->distancia_desde_fin }} km</p>
    <p><strong>Descripción:</strong> {{ $localizacion->descripcion ?? 'Sin descripción' }}</p>
    <p><strong>Activo:</strong> {{ $localizacion->activo ? 'Sí' : 'No' }}</p>

    <h3>Alojamientos</h3>
    @if ($localizacion->alojamientos->count())
        <ul>
            @foreach ($localizacion->alojamientos as $alojamiento)
                <li>{{ $alojamiento->nombre }} - {{ $alojamiento->tipo }}</li>
            @endforeach
        </ul>
    @else
        <p>Sin alojamientos registrados.</p>
    @endif

    <hr>

    <h3>Comentarios</h3>

    @if ($localizacion->comentarios->count())
        @foreach ($localizacion->comentarios as $comentario)
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
        <form action="{{ route('localizaciones.comentarios.store', $localizacion) }}" method="POST">
            @csrf
            <textarea name="texto" rows="3" style="width:100%;" placeholder="Escribe tu comentario..." required></textarea>
            <button type="submit">Enviar comentario</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">Inicia sesión</a> para dejar un comentario.</p>
    @endauth

    <a href="{{ route('localizaciones.edit', $localizacion) }}">Editar</a>
    <a href="{{ route('localizaciones.index') }}">Volver</a>
@endsection
