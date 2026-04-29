@extends('components.layout')

@section('title', 'Listado de Rutas')
@section('content')
    <h1>Rutas</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Dificultad</th>
                <th>Kilómetros</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rutas as $ruta)
            <tr>
                <td>{{ $ruta->id }}</td>
                <td>
                    @if($ruta->imagen)
                        <img src="{{ Storage::url($ruta->imagen) }}" alt="{{ $ruta->nombre }}">
                    @else
                        Sin imagen
                    @endif
                </td>
                <td>{{ $ruta->nombre }}</td>
                <td>{{ $ruta->dificultad }}</td>
                <td>{{ $ruta->kilometros }} km</td>
                <td>
                    <a href="{{ route('rutas.show', $ruta) }}">Ver</a>
                    <a href="{{ route('rutas.edit', $ruta) }}">Editar</a>
                    <form action="{{ route('rutas.destroy', $ruta) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Eliminar esta ruta?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
