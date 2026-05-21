import React from 'react';

export default function FormularioPlanificador({
  rutas,
  selectedRuta,
  setSelectedRuta,
  localizaciones,
  inicioId,
  setInicioId,
  finId,
  setFinId,
  kmDia,
  setKmDia,
  fechaInicio,
  setFechaInicio,
  onSubmit,
  loading
}) {
  return (
    <div className="card shadow-sm border-0 p-4 mb-4" style={{ borderRadius: 'var(--radius-lg)' }}>
      <form onSubmit={onSubmit}>
        <div className="row g-3">
          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark">Ruta:</label>
            <select className="form-select" value={selectedRuta} onChange={(e) => setSelectedRuta(e.target.value)} required>
              <option value="">Selecciona una ruta</option>
              {rutas.map(ruta => (
                <option key={ruta.id} value={ruta.id}>{ruta.nombre}</option>
              ))}
            </select>
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark">Punto de inicio:</label>
            <select className="form-select" value={inicioId} onChange={(e) => setInicioId(e.target.value)} required disabled={!selectedRuta}>
              <option value="">Selecciona inicio</option>
              {localizaciones.map(loc => (
                <option key={loc.id} value={loc.id}>
                  {loc.nombre} ({loc.distancia_desde_inicio} km)
                </option>
              ))}
            </select>
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark">Punto de fin (opcional):</label>
            <select className="form-select" value={finId} onChange={(e) => setFinId(e.target.value)} disabled={!selectedRuta}>
              <option value="">Hasta el final</option>
              {localizaciones.map(loc => (
                <option key={loc.id} value={loc.id}>
                  {loc.nombre} ({loc.distancia_desde_inicio} km)
                </option>
              ))}
            </select>
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark">Kilómetros por día:</label>
            <input
              type="number"
              className="form-control"
              value={kmDia}
              onChange={(e) => setKmDia(e.target.value)}
              min="1"
              max="100"
              required
            />
          </div>

          <div className="col-12">
            <label className="form-label fw-bold text-dark">Fecha de inicio (necesaria para guardar):</label>
            <input
              type="date"
              className="form-control"
              value={fechaInicio}
              onChange={(e) => setFechaInicio(e.target.value)}
            />
          </div>
        </div>

        <button 
          type="submit" 
          className="btn text-white fw-bold w-100 mt-4 py-2" 
          disabled={loading}
          style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}
        >
          {loading ? 'Calculando...' : 'Calcular etapas'}
        </button>
      </form>
    </div>
  );
}