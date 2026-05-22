import React from 'react';
import Button from '../ui/Button';

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
  loading
}) {
  // 🔥 Capturamos el día de hoy en formato YYYY-MM-DD para bloquear el pasado
  const hoy = new Date().toISOString().split('T')[0];

  return (
    <div className="card shadow-sm border-0 p-4 mb-4 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
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

          <div className="col-12 col-md-6">
            <label className="form-label fw-bold text-dark small">Punto de fin (opcional):</label>
            <select className="form-select" value={finId} onChange={(e) => setFinId(e.target.value)} disabled={!selectedRuta}>
              <option value="">Hasta el final del camino</option>
              {localizaciones.map(loc => (
                <option key={loc.id} value={loc.id}>
                  {loc.nombre} ({loc.distancia_desde_inicio} km)
                </option>
              ))}
            </select>
          </div>

          <div className="col-12 col-md-6">
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

          <div className="col-12">
            <label className="form-label fw-bold text-dark small">Fecha de inicio de caminata:</label>
            <input
              type="date"
              className="form-control"
              value={fechaInicio}
              onChange={(e) => setFechaInicio(e.target.value)}
              min={hoy} // 👈 Control nativo: fechas anteriores a hoy se quedan deshabilitadas
            />
          </div>
        </div>

        {/* Inyección de tu botón principal color tierra con sombras y transiciones */}
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