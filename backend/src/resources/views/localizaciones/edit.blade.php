@extends('components.layout')

@section('title', 'Editar Localización')
@section('content')
    <h1>Editar localización: {{ $localizacion->nombre }}</h1>

    <form action="{{ route('localizaciones.update', $localizacion) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Ruta:</label>
            <select name="ruta_id" required>
                @foreach($rutas as $ruta)
                    <option value="{{ $ruta->id }}" {{ old('ruta_id', $localizacion->ruta_id) == $ruta->id ? 'selected' : '' }}>
                        {{ $ruta->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre', $localizacion->nombre) }}">
        </div>

        <div>
            <label>Distancia desde inicio (km):</label>
            <input type="number" name="distancia_desde_inicio" step="0.01" required value="{{ old('distancia_desde_inicio', $localizacion->distancia_desde_inicio) }}">
        </div>

        <div>
            <label>Distancia hasta el final (km):</label>
            <input type="number" name="distancia_desde_fin" step="0.01" required value="{{ old('distancia_desde_fin', $localizacion->distancia_desde_fin) }}">
        </div>

        <div>
            <label>Descripción:</label>
            <textarea name="descripcion">{{ old('descripcion', $localizacion->descripcion) }}</textarea>
        </div>

        <div>
            <label>Activo:</label>
            <input type="checkbox" name="activo" value="1" {{ old('activo', $localizacion->activo) ? 'checked' : '' }}>
        </div>

        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('localizaciones.index') }}">Volver</a>
@endsection