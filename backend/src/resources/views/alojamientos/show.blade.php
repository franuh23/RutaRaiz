@extends('components.layout')

@section('title', $alojamiento->nombre)
@section('content')
    <h1>{{ $alojamiento->nombre }}</h1>

    @if($alojamiento->imagen)
        <img src="{{ Storage::url($alojamiento->imagen) }}" alt="{{ $alojamiento->nombre }}" style="max-width: 300px;">
    @endif

    <p><strong>Localización:</strong> {{ $alojamiento->localizacion->nombre }} ({{ $alojamiento->localizacion->ruta->nombre }})</p>
    <p><strong>Dirección:</strong> {{ $alojamiento->direccion ?? 'No especificada' }}</p>
    <p><strong>Tipo:</strong> {{ $alojamiento->tipo }}</p>
    <p><strong>Enlace web:</strong> 
        @if($alojamiento->enlace)
            <a href="{{ $alojamiento->enlace }}" target="_blank">{{ $alojamiento->enlace }}</a>
        @else
            No disponible
        @endif
    </p>
    <p><strong>Teléfono:</strong> {{ $alojamiento->telefono ?? 'No disponible' }}</p>
    <p><strong>Email:</strong> {{ $alojamiento->email ?? 'No disponible' }}</p>
    <p><strong>Activo:</strong> {{ $alojamiento->activo ? 'Sí' : 'No' }}</p>

    <h3>Comentarios</h3>
    @if($alojamiento->comentarios->count())
        @foreach($alojamiento->comentarios as $comentario)
            <div style="border-bottom: 1px solid #ccc; margin-bottom: 10px;">
                <strong>Usuario {{ $comentario->usuario_id }}</strong> - {{ $comentario->fecha }}
                <p>{{ $comentario->texto }}</p>
            </div>
        @endforeach
    @else
        <p>Sin comentarios aún.</p>
    @endif

    <a href="{{ route('alojamientos.edit', $alojamiento) }}">Editar</a>
    <a href="{{ route('alojamientos.index') }}">Volver</a>
@endsection