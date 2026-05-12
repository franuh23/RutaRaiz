@extends('components.layout')

@section('title', 'Mis Planificaciones')
@section('content')
    <h1>Mis Planificaciones</h1>

    <a href="{{ route('planificaciones.create') }}">Nueva planificación</a>

    @if($planificaciones->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ruta</th>
                    <th>Fecha inicio</th>
                    <th>Km/día</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planificaciones as $planificacion)
                <tr>
                    <td>{{ $planificacion->id }}</td>
                    <td>{{ $planificacion->ruta->nombre }}</td>
                    <td>{{ $planificacion->fecha_inicio }}</td>
                    <td>{{ $planificacion->km_dia }} km/día</td>
                    <td>
                        <a href="{{ route('planificaciones.show', $planificacion) }}">Ver</a>
                        <a href="{{ route('planificaciones.edit', $planificacion) }}">Editar</a>
                        <form action="{{ route('planificaciones.destroy', $planificacion) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $planificaciones->links() }}
    @else
        <p>No tienes planificaciones guardadas.</p>
    @endif
@endsection
