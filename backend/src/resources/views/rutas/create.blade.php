@extends('components.layout')

@section('title', 'Crear Ruta')
@section('content')
    <h1>Crear nueva ruta</h1>

    <form action="{{ route('rutas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre') }}">
        </div>

        <div>
            <label>Descripción:</label>
            <textarea name="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <div>
            <label>Dificultad:</label>
            <select name="dificultad" required>
                <option value="baja">Baja</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
            </select>
        </div>

        <div>
            <label>Punto de inicio:</label>
            <input type="text" name="inicio" required maxlength="100" value="{{ old('inicio') }}">
        </div>

        <div>
            <label>Punto de fin:</label>
            <input type="text" name="fin" required maxlength="100" value="{{ old('fin') }}">
        </div>

        <div>
            <label>Kilómetros:</label>
            <input type="number" name="kilometros" step="0.01" required value="{{ old('kilometros') }}">
        </div>

        <div>
            <label>Imagen:</label>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('rutas.index') }}">Volver</a>
@endsection
