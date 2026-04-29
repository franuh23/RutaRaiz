@extends('components.layout')

@section('title', $ruta->nombre)
@section('content')
    <h1>{{ $ruta->nombre }}</h1>

    @if($ruta->imagen)
        <img src="{{ Storage::url($ruta->imagen) }}" alt="{{ $ruta->nombre }}" style="max-width: 400px;">
    @endif

    <p><strong>Descripción:</strong> {{ $ruta->descripcion ?? 'Sin descripción' }}</p>
    <p><strong>Dificultad:</strong> {{ $ruta->dificultad }}</p>
    <p><strong>Inicio:</strong> {{ $ruta->inicio }}</p>
    <p><strong>Fin:</strong> {{ $ruta->fin }}</p>
    <p><strong>Kilómetros:</strong> {{ $ruta->kilometros }} km</p>

    <h3>Localizaciones</h3>
    @if($ruta->localizaciones->count())
        <ul>
            @foreach($ruta->localizaciones as $localizacion)
                <li>{{ $localizacion->nombre }} - {{ $localizacion->distancia_desde_inicio }} km desde inicio</li>
            @endforeach
        </ul>
    @else
        <p>Sin localizaciones registradas.</p>
    @endif

    <h3>Comentarios</h3>
    @if($ruta->comentarios->count())
        @foreach($ruta->comentarios as $comentario)
            <div style="border-bottom: 1px solid #ccc; margin-bottom: 10px;">
                <strong>Usuario {{ $comentario->usuario_id }}</strong> - {{ $comentario->fecha }}
                <p>{{ $comentario->texto }}</p>
            </div>
        @endforeach
    @else
        <p>Sin comentarios aún.</p>
    @endif

    <a href="{{ route('rutas.edit', $ruta) }}">Editar</a>
    <a href="{{ route('rutas.index') }}">Volver al listado</a>
@endsection
