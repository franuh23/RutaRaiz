@extends('components.layout')

@section('title', 'Alojamientos')
@section('content')
    <h1>Alojamientos</h1>

    <a href="{{ route('alojamientos.create') }}">Nuevo alojamiento</a>

    @if($alojamientos->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Localización</th>
                    <th>Tipo</th>
                    <th>Teléfono</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alojamientos as $alojamiento)
                <tr>
                    <td>{{ $alojamiento->id }}</td>
                    <td>
                        @if($alojamiento->imagen)
                            <img src="{{ Storage::url($alojamiento->imagen) }}" alt="{{ $alojamiento->nombre }}" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            Sin imagen
                        @endif
                    </td>
                    <td>{{ $alojamiento->nombre }}</td>
                    <td>{{ $alojamiento->localizacion->nombre }} ({{ $alojamiento->localizacion->ruta->nombre }})</td>
                    <td>{{ $alojamiento->tipo }}</td>
                    <td>{{ $alojamiento->telefono ?? '-' }}</td>
                    <td>{{ $alojamiento->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        <a href="{{ route('alojamientos.show', $alojamiento) }}">Ver</a>
                        <a href="{{ route('alojamientos.edit', $alojamiento) }}">Editar</a>
                        <form action="{{ route('alojamientos.destroy', $alojamiento) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este alojamiento?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $alojamientos->links() }}
    @else
        <p>No hay alojamientos registrados.</p>
    @endif
@endsection