@extends('components.layout')

@section('title', 'Editar Planificación')
@section('content')
    <h1>Editar planificación</h1>

    <form action="{{ route('planificaciones.update', $planificacion) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Ruta:</label>
            <select name="ruta_id" id="ruta_id" required>
                @foreach($rutas as $ruta)
                    <option value="{{ $ruta->id }}" {{ old('ruta_id', $planificacion->ruta_id) == $ruta->id ? 'selected' : '' }}>
                        {{ $ruta->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Punto de inicio:</label>
            <select name="localizacion_inicio_id" id="localizacion_inicio_id" required>
                <option value="">-- Selecciona un punto --</option>
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
            <input type="date" name="fecha_inicio" required value="{{ old('fecha_inicio', $planificacion->fecha_inicio) }}">
        </div>

        <div>
            <label>Kilómetros por día:</label>
            <input type="number" name="km_dia" step="1" min="1" max="100" required value="{{ old('km_dia', $planificacion->km_dia) }}">
        </div>

        <button type="submit">Actualizar</button>
    </form>

    <a href="{{ route('planificaciones.index') }}">Volver</a>

    <script>
        const rutas = @json($rutas);
        const localizacionesPorRuta = {};

        @foreach($rutas as $ruta)
            localizacionesPorRuta[{{ $ruta->id }}] = @json($ruta->localizaciones);
        @endforeach

        const inicioActual = {{ $planificacion->localizacion_inicio_id }};
        const finActual = {{ $planificacion->localizacion_fin_id ?? 'null' }};

        function cargarLocalizaciones() {
            const rutaId = document.getElementById('ruta_id').value;
            const inicioSelect = document.getElementById('localizacion_inicio_id');
            const finSelect = document.getElementById('localizacion_fin_id');

            inicioSelect.innerHTML = '<option value="">-- Selecciona un punto --</option>';
            finSelect.innerHTML = '<option value="">-- Sin definir --</option>';

            if (rutaId && localizacionesPorRuta[rutaId]) {
                localizacionesPorRuta[rutaId].forEach(loc => {
                    inicioSelect.innerHTML += `<option value="${loc.id}" ${inicioActual == loc.id ? 'selected' : ''}>${loc.nombre} (${loc.distancia_desde_inicio} km)</option>`;
                    finSelect.innerHTML += `<option value="${loc.id}" ${finActual == loc.id ? 'selected' : ''}>${loc.nombre} (${loc.distancia_desde_inicio} km)</option>`;
                });
            }
        }

        document.getElementById('ruta_id').addEventListener('change', cargarLocalizaciones);
        cargarLocalizaciones();
    </script>
@endsection
