import React from 'react';

export default function PlanificacionCard({ p, onVer, onEliminar }) {
  return (
    <div className="card shadow-sm border-0 p-3 mb-3" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="row align-items-center g-3">
        <div className="col-12 col-md-8">
          <h3 className="h5 mb-2" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
            {p.ruta_nombre}
          </h3>
          <div className="d-flex flex-wrap gap-3 text-muted small mb-2">
            <span>📅 Inicio: {p.fecha_inicio}</span>
            <span>👣 {p.km_dia} km/día</span>
            <span>🗓️ {p.dias_totales} días</span>
          </div>
          <div className="text-muted small">
            <span>📍 {p.localizacion_inicio_nombre} → {p.localizacion_fin_nombre || 'Final de ruta'}</span>
          </div>
        </div>
        <div className="col-12 col-md-4 d-flex justify-content-md-end gap-2">
          <button 
            className="btn btn-sm text-white px-3" 
            onClick={() => onVer(p.id)}
            style={{ background: 'var(--verde-medio)', fontWeight: '600', borderRadius: 'var(--radius-md)' }}
          >
            Ver etapas
          </button>
          <button 
            className="btn btn-sm btn-outline-danger px-3" 
            onClick={() => onEliminar(p.id)}
            style={{ fontWeight: '600', borderRadius: 'var(--radius-md)' }}
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  );
}