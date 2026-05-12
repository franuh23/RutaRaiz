@extends('components.layout')

@section('title', 'Crear Localización')
@section('content')
    <h1>Crear nueva localización</h1>

    <form action="{{ route('localizaciones.store') }}" method="POST">
        @csrf

        <div>
            <label>Ruta:</label>
            <select name="ruta_id" required>
                <option value="">-- Selecciona una ruta --</option>
                @foreach($rutas as $ruta)
                    <option value="{{ $ruta->id }}" {{ old('ruta_id') == $ruta->id ? 'selected' : '' }}>
                        {{ $ruta->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre') }}">
        </div>

        <div>
            <label>Distancia desde inicio (km):</label>
            <input type="number" name="distancia_desde_inicio" step="0.01" required value="{{ old('distancia_desde_inicio') }}">
        </div>

        <div>
            <label>Distancia hasta el final (km):</label>
            <input type="number" name="distancia_desde_fin" step="0.01" required value="{{ old('distancia_desde_fin') }}">
        </div>

        <div>
            <label>Descripción:</label>
            <textarea name="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <div>
            <label>Activo:</label>
            <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
        </div>

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('localizaciones.index') }}">Volver</a>
@endsection