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
    @if($localizacion->alojamientos->count())
        <ul>
            @foreach($localizacion->alojamientos as $alojamiento)
                <li>{{ $alojamiento->nombre }} - {{ $alojamiento->tipo }}</li>
            @endforeach
        </ul>
    @else
        <p>Sin alojamientos registrados.</p>
    @endif

    <a href="{{ route('localizaciones.edit', $localizacion) }}">Editar</a>
    <a href="{{ route('localizaciones.index') }}">Volver</a>
@endsection