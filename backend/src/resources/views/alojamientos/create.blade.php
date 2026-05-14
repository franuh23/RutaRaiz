@extends('components.layout')

@section('title', 'Crear Alojamiento')
@section('content')
    <h1>Crear nuevo alojamiento</h1>

    <form action="{{ route('alojamientos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Localización:</label>
            <select name="localizacion_id" required>
                <option value="">-- Selecciona una localización --</option>
                @foreach($localizaciones as $localizacion)
                    <option value="{{ $localizacion->id }}" {{ old('localizacion_id') == $localizacion->id ? 'selected' : '' }}>
                        {{ $localizacion->nombre }} ({{ $localizacion->ruta->nombre }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre') }}">
        </div>

        <div>
            <label>Dirección:</label>
            <input type="text" name="direccion" maxlength="255" value="{{ old('direccion') }}">
        </div>

        <div>
            <label>Tipo:</label>
            <select name="tipo" required>
                <option value="">-- Selecciona un tipo --</option>
                <option value="hostal" {{ old('tipo') == 'hostal' ? 'selected' : '' }}>Hostal</option>
                <option value="hotel" {{ old('tipo') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                <option value="albergue" {{ old('tipo') == 'albergue' ? 'selected' : '' }}>Albergue</option>
                <option value="casa_rural" {{ old('tipo') == 'casa_rural' ? 'selected' : '' }}>Casa Rural</option>
                <option value="camping" {{ old('tipo') == 'camping' ? 'selected' : '' }}>Camping</option>
            </select>
        </div>

        <div>
            <label>Enlace web:</label>
            <input type="url" name="enlace" maxlength="255" value="{{ old('enlace') }}" placeholder="https://...">
        </div>

        <div>
            <label>Teléfono:</label>
            <input type="text" name="telefono" maxlength="20" value="{{ old('telefono') }}">
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" maxlength="100" value="{{ old('email') }}">
        </div>

        <div>
            <label>URL de la imagen:</label>
            <input type="text" name="imagen" placeholder="https://ejemplo.com/imagen.jpg" value="{{ old('imagen') }}">
        </div>

        <div>
            <label>Activo:</label>
            <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
        </div>

        <button type="submit">Guardar</button>
    </form>

    <a href="{{ route('alojamientos.index') }}">Volver</a>
@endsection