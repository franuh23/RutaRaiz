@extends('components.layout')

@section('title', 'Editar Ruta')
@section('content')
    <h1>Editar ruta: {{ $ruta->nombre }}</h1>

    <form action="{{ route('rutas.update', $ruta) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre', $ruta->nombre) }}">
        </div>

        <div>
            <label>Descripción:</label>
            <textarea name="descripcion">{{ old('descripcion', $ruta->descripcion) }}</textarea>
        </div>

        <div>
            <label>Dificultad:</label>
            <select name="dificultad" required>
                <option value="baja" {{ $ruta->dificultad == 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="media" {{ $ruta->dificultad == 'media' ? 'selected' : '' }}>Media</option>
                <option value="alta" {{ $ruta->dificultad == 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
        </div>

        <div>
            <label>Punto de inicio:</label>
            <input type="text" name="inicio" required maxlength="100" value="{{ old('inicio', $ruta->inicio) }}">
        </div>

        <div>
            <label>Punto de fin:</label>
            <input type="text" name="fin" required maxlength="100" value="{{ old('fin', $ruta->fin) }}">
        </div>

        <div>
            <label>Kilómetros:</label>
            <input type="number" name="kilometros" step="0.01" required value="{{ old('kilometros', $ruta->kilometros) }}">
        </div>

        <div>
            <label>URL de la imagen:</label>
            <input type="text" name="imagen" placeholder="https://ejemplo.com/imagen.jpg"
                value="{{ old('imagen', $ruta->imagen) }}">
        </div>

        <div>
            <label>Cambiar imagen:</label>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('rutas.index') }}">Volver</a>
@endsection