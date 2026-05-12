@extends('components.layout')

@section('title', 'Localizaciones')
@section('content')
    <h1>Localizaciones</h1>

    <a href="{{ route('localizaciones.create') }}">Nueva localización</a>

    @if($localizaciones->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ruta</th>
                    <th>Nombre</th>
                    <th>Distancia inicio</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($localizaciones as $localizacion)
                <tr>
                    <td>{{ $localizacion->id }}</td>
                    <td>{{ $localizacion->ruta->nombre }}</td>
                    <td>{{ $localizacion->nombre }}</td>
                    <td>{{ $localizacion->distancia_desde_inicio }} km</td>
                    <td>{{ $localizacion->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('localizaciones.show', $localizacion) }}">Ver</a>
                        <a href="{{ route('localizaciones.edit', $localizacion) }}">Editar</a>
                        <form action="{{ route('localizaciones.destroy', $localizacion) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $localizaciones->links() }}
    @else
        <p>No hay localizaciones registradas.</p>
    @endif
@endsection