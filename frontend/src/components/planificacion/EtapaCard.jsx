import React, { useState } from 'react';
import { Link } from 'react-router-dom';

export default function EtapaCard({ etapa }) {
  const [isOpen, setIsOpen] = useState(false);
  const tieneAlojamientos = etapa.alojamientos && etapa.alojamientos.length > 0;

  return (
    <div 
      className="card shadow-sm border-0 mb-3" 
      style={{ 
        borderRadius: 'var(--radius-lg)', 
        overflow: 'hidden'
      }}
    >
      {/* Cabecera de la Etapa */}
      <div 
        className="p-3 bg-white d-flex align-items-center justify-content-between flex-wrap gap-3"
        style={{ cursor: tieneAlojamientos ? 'pointer' : 'default' }}
        onClick={() => tieneAlojamientos && setIsOpen(!isOpen)}
      >
        <div className="d-flex align-items-center gap-3">
          {/* Indicador de Día */}
          <div className="d-flex flex-column align-items-center justify-content-center text-white text-center" 
               style={{ 
                 background: 'linear-gradient(135deg, var(--verde-bosque), var(--verde-hoja))', 
                 width: '54px', 
                 height: '54px', 
                 borderRadius: 'var(--radius-md)',
                 fontSize: '0.65rem',
                 letterSpacing: '0.05em'
               }}>
            <span className="fw-medium opacity-75">DÍA</span>
            <strong className="fs-4 lh-1 fw-bold">{etapa.dia}</strong>
          </div>

          {/* Trayecto Geográfico */}
          <div>
            <div className="d-flex align-items-center gap-2 flex-wrap">
              <span className="fw-bold text-dark fs-5">{etapa.inicio}</span>
              <span className="fw-bold fs-5 text-muted opacity-50">→</span>
              <span className="fw-bold fs-5" style={{ color: 'var(--verde-bosque)' }}>{etapa.fin}</span>
            </div>
            {tieneAlojamientos ? (
              <small className="text-muted d-block mt-1">
                {isOpen ? '🔼 Ocultar servicios disponibles' : `🔽 Click para ver ${etapa.alojamientos.length} alojamientos en ${etapa.fin}`}
              </small>
            ) : (
              <small className="text-muted d-block mt-1">⚠️ Sin alojamientos registrados en esta parada</small>
            )}
          </div>
        </div>

        {/* Métrica de Distancia */}
        <div className="d-flex align-items-center gap-2">
          <span className="badge px-3 py-2 text-success fw-bold fs-6" style={{ background: 'var(--crema-oscura)', borderRadius: 'var(--radius-full)' }}>
            {etapa.distancia} km
          </span>
        </div>
      </div>

      {/* Desplegable de Alojamientos Asociados */}
      {tieneAlojamientos && isOpen && (
        <div className="card-body bg-light border-top p-3">
          <h6 className="text-uppercase text-muted fw-bold mb-3" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
            🏡 Dónde dormir en {etapa.fin}:
          </h6>
          <div className="row g-2">
            {etapa.alojamientos.map((aloj) => (
              <div className="col-12 col-md-6" key={aloj.id}>
                <div 
                  className="card border-0 bg-white p-3 h-100 d-flex flex-row justify-content-between align-items-center shadow-sm"
                  style={{ borderRadius: 'var(--radius-md)' }}
                >
                  <div>
                    <span className="badge bg-secondary mb-1 small" style={{ fontSize: '0.7rem' }}>{aloj.tipo}</span>
                    <h6 className="mb-0 fw-bold text-dark">{aloj.nombre}</h6>
                  </div>
                  <div className="text-end d-flex flex-column align-items-end gap-2">
                    <Link 
                      to={`/alojamientos/${aloj.id}`} 
                      className="btn btn-sm btn-outline-primary px-3 fw-semibold text-decoration-none"
                      style={{ borderRadius: 'var(--radius-sm)', fontSize: '0.8rem' }}
                    >
                      Ver detalles
                    </Link>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}