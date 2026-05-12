@extends('components.layout')

@section('title', 'Crear Planificación')
@section('content')
    <h1>Nueva planificación</h1>

    <form action="{{ route('planificaciones.store') }}" method="POST">
        @csrf

        <div>
            <label>Ruta:</label>
            <select name="ruta_id" id="ruta_id" required>
                <option value="">-- Selecciona una ruta --</option>
                @foreach($rutas as $ruta)
                    <option value="{{ $ruta->id }}" {{ old('ruta_id') == $ruta->id ? 'selected' : '' }}>
                        {{ $ruta->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Punto de inicio:</label>
            <select name="localizacion_inicio_id" id="localizacion_inicio_id" required>
                <option value="">-- Primero selecciona una ruta --</option>
            </select>
        </div>

        <div>
            <label>Punto de fin (opcional):</label>
            <select name="localizacion_fin_id" id="localizacion_fin_id">
                <option value="">-- Sin definir --</option>
            </select>
        </div>

        <div>
            <label>Fecha de inicio:</label>
            <input type="date" name="fecha_inicio" required value="{{ old('fecha_inicio') }}">
        </div>

        <div>
            <label>Kilómetros por día:</label>
            <input type="number" name="km_dia" step="1" min="1" max="100" required value="{{ old('km_dia', 20) }}">
            <small>Entre 1 y 100 km</small>
        </div>

        <button type="submit">Crear planificación</button>
    </form>

    <a href="{{ route('planificaciones.index') }}">Volver</a>

    <script>
        const rutas = @json($rutas);
        const localizacionesPorRuta = {};

        @foreach($rutas as $ruta)
            localizacionesPorRuta[{{ $ruta->id }}] = @json($ruta->localizaciones);
        @endforeach

        document.getElementById('ruta_id').addEventListener('change', function() {
            const rutaId = this.value;
            const inicioSelect = document.getElementById('localizacion_inicio_id');
            const finSelect = document.getElementById('localizacion_fin_id');

            inicioSelect.innerHTML = '<option value="">-- Selecciona un punto --</option>';
            finSelect.innerHTML = '<option value="">-- Sin definir --</option>';

            if (rutaId && localizacionesPorRuta[rutaId]) {
                localizacionesPorRuta[rutaId].forEach(loc => {
                    inicioSelect.innerHTML += `<option value="${loc.id}">${loc.nombre} (${loc.distancia_desde_inicio} km)</option>`;
                    finSelect.innerHTML += `<option value="${loc.id}">${loc.nombre} (${loc.distancia_desde_inicio} km)</option>`;
                });
            }
        });
    </script>
@endsection
