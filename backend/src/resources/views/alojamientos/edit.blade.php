@extends('components.layout')

@section('title', 'Editar Alojamiento')
@section('content')
    <h1>Editar alojamiento: {{ $alojamiento->nombre }}</h1>

    <form action="{{ route('alojamientos.update', $alojamiento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label>Localización:</label>
            <select name="localizacion_id" required>
                @foreach($localizaciones as $localizacion)
                    <option value="{{ $localizacion->id }}" {{ old('localizacion_id', $alojamiento->localizacion_id) == $localizacion->id ? 'selected' : '' }}>
                        {{ $localizacion->nombre }} ({{ $localizacion->ruta->nombre }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required maxlength="150" value="{{ old('nombre', $alojamiento->nombre) }}">
        </div>

        <div>
            <label>Dirección:</label>
            <input type="text" name="direccion" maxlength="255" value="{{ old('direccion', $alojamiento->direccion) }}">
        </div>

        <div>
            <label>Tipo:</label>
            <select name="tipo" required>
                <option value="hostal" {{ old('tipo', $alojamiento->tipo) == 'hostal' ? 'selected' : '' }}>Hostal</option>
                <option value="hotel" {{ old('tipo', $alojamiento->tipo) == 'hotel' ? 'selected' : '' }}>Hotel</option>
                <option value="albergue" {{ old('tipo', $alojamiento->tipo) == 'albergue' ? 'selected' : '' }}>Albergue</option>
                <option value="casa_rural" {{ old('tipo', $alojamiento->tipo) == 'casa_rural' ? 'selected' : '' }}>Casa Rural</option>
                <option value="camping" {{ old('tipo', $alojamiento->tipo) == 'camping' ? 'selected' : '' }}>Camping</option>
            </select>
        </div>

        <div>
            <label>Enlace web:</label>
            <input type="url" name="enlace" maxlength="255" value="{{ old('enlace', $alojamiento->enlace) }}" placeholder="https://...">
        </div>

        <div>
            <label>Teléfono:</label>
            <input type="text" name="telefono" maxlength="20" value="{{ old('telefono', $alojamiento->telefono) }}">
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" maxlength="100" value="{{ old('email', $alojamiento->email) }}">
        </div>

        <div>
            <label>Imagen actual:</label>
            @if($alojamiento->imagen)
                <img src="{{ Storage::url($alojamiento->imagen) }}" alt="{{ $alojamiento->nombre }}" style="max-width: 150px;">
            @else
                <p>Sin imagen</p>
            @endif
        </div>

        <div>
            <label>Cambiar imagen:</label>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <div>
            <label>Activo:</label>
            <input type="checkbox" name="activo" value="1" {{ old('activo', $alojamiento->activo) ? 'checked' : '' }}>
        </div>

        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('alojamientos.index') }}">Volver</a>
@endsection