import React from 'react';
import Button from '../ui/Button';
// Formulario dinámico del planificador.
// Modifica los inputs visibles según el tipoPlanificacion seleccionado y envía las variables de itinerario mediante onSubmit.

export default function FormularioPlanificador({
  rutas = [],
  selectedRuta,
  setSelectedRuta,
  localizaciones = [],
  inicioId,
  setInicioId,
  finId,
  setFinId,
  kmDia,
  setKmDia,
  fechaInicio,
  setFechaInicio,
  onSubmit,
  loading,
  tipoPlanificacion,
  setTipoPlanificacion,
  diasDisponibles,
  setDiasDisponibles
}) {
  const hoy = new Date().toISOString().split('T')[0];

  return (
    <div className="card shadow-sm border-0 p-4 mb-4 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      
      <div className="d-flex btn-group mb-4 bg-light p-1" style={{ borderRadius: 'var(--radius-md)' }}>
        <button
          type="button"
          className={`btn btn-sm fw-bold ${tipoPlanificacion === 'destino_ritmo' ? 'bg-white shadow text-dark' : 'text-muted border-0'}`}
          onClick={() => setTipoPlanificacion('destino_ritmo')}
          style={{ borderRadius: 'var(--radius-sm)', transition: 'all 0.2s' }}
        >
          🎯 Clásico (Fijar Destino + Ritmo)
        </button>
        <button
          type="button"
          className={`btn btn-sm fw-bold ${tipoPlanificacion === 'dias_ritmo' ? 'bg-white shadow text-dark' : 'text-muted border-0'}`}
          onClick={() => setTipoPlanificacion('dias_ritmo')}
          style={{ borderRadius: 'var(--radius-sm)', transition: 'all 0.2s' }}
        >
          ⏳ Días limitados (Fijar Días + Ritmo)
        </button>
        <button
          type="button"
          className={`btn btn-sm fw-bold ${tipoPlanificacion === 'destino_dias' ? 'bg-white shadow text-dark' : 'text-muted border-0'}`}
          onClick={() => setTipoPlanificacion('destino_dias')}
          style={{ borderRadius: 'var(--radius-sm)', transition: 'all 0.2s' }}
        >
          🚀 Reto Crono (Fijar Destino + Días)
        </button>
      </div>

      <form onSubmit={onSubmit}>
        <div className="row g-3">
          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark small">Selecciona tu ruta:</label>
            <select className="form-select" value={selectedRuta} onChange={(e) => setSelectedRuta(e.target.value)} required>
              <option value="">Selecciona una opción</option>
              {rutas.map(ruta => (
                <option key={ruta.id} value={ruta.id}>{ruta.nombre}</option>
              ))}
            </select>
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark small">Punto de inicio:</label>
            <select className="form-select" value={inicioId} onChange={(e) => setInicioId(e.target.value)} required disabled={!selectedRuta}>
              <option value="">Selecciona hito de salida</option>
              {localizaciones.map(loc => (
                <option key={loc.id} value={loc.id}>
                  {loc.nombre} ({loc.distancia_desde_inicio} km)
                </option>
              ))}
            </select>
          </div>

          {tipoPlanificacion !== 'dias_ritmo' && (
            <div className="col-12 col-md-6 animate__animated animate__fadeIn">
              <label className="form-label fw-bold text-dark small">Punto de fin:</label>
              <select 
                className="form-select" 
                value={finId} 
                onChange={(e) => setFinId(e.target.value)} 
                disabled={!selectedRuta}
                required={tipoPlanificacion === 'destino_dias'}
              >
                <option value="">{tipoPlanificacion === 'destino_ritmo' ? 'Hasta el final del camino (Opcional)' : 'Selecciona hito de destino (Obligatorio)'}</option>
                {localizaciones.map(loc => (
                  <option key={loc.id} value={loc.id}>
                    {loc.nombre} ({loc.distancia_desde_inicio} km)
                  </option>
                ))}
              </select>
            </div>
          )}

          {tipoPlanificacion !== 'destino_ritmo' && (
            <div className="col-12 col-md-6 animate__animated animate__fadeIn">
              <label className="form-label fw-bold text-dark small">Días disponibles para caminar:</label>
              <input
                type="number"
                className="form-control"
                value={diasDisponibles}
                onChange={(e) => setDiasDisponibles(Number(e.target.value) || '')}
                min="1"
                max="90"
                required
              />
            </div>
          )}

          {tipoPlanificacion !== 'destino_dias' && (
            <div className="col-12 col-md-6 animate__animated animate__fadeIn">
              <label className="form-label fw-bold text-dark small">Ritmo de marcha (Km / día):</label>
              <input
                type="number"
                className="form-control"
                value={kmDia}
                onChange={(e) => setKmDia(Number(e.target.value) || '')}
                min="1"
                max="100"
                required
              />
            </div>
          )}

          <div className="col-12">
            <label className="form-label fw-bold text-dark small">Fecha de inicio de caminata:</label>
            <input
              type="date"
              className="form-control"
              value={fechaInicio}
              onChange={(e) => setFechaInicio(e.target.value)}
              min={hoy}
              required
            />
          </div>
        </div>

        <Button 
          type="submit" 
          variant="primary" 
          className="w-100 mt-4 py-2 text-white fw-bold"
          disabled={loading}
        >
          {loading ? 'Calculando etapas del Camino...' : '👣 Calcular mi itinerario'}
        </Button>
      </form>
    </div>
  );
}