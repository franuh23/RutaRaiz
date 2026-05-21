import React from 'react';

export default function EtapaCard({ etapa }) {
  return (
    <div className="card shadow-sm border-0 p-3 mb-2" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="d-flex align-items-center gap-3">
        <div className="d-flex flex-column align-items-center justify-content-center text-white p-2 text-center" 
             style={{ 
               background: 'linear-gradient(135deg, var(--verde-bosque), var(--verde-hoja))', 
               width: '56px', 
               height: '56px', 
               borderRadius: 'var(--radius-md)',
               fontSize: '0.7rem',
               letterSpacing: '0.05em'
             }}>
          <span>DÍA</span>
          <strong className="fs-4 lh-1">{etapa.dia}</strong>
        </div>
        <div className="d-flex flex-wrap align-items-center justify-content-between flex-grow-1 gap-2">
          <div className="d-flex flex-wrap align-items-center gap-2">
            <span className="fw-bold text-dark">{etapa.localizacion_inicio_nombre}</span>
            <span className="fw-bold" style={{ color: 'var(--oro)' }}>→</span>
            <span className="fw-bold text-dark">{etapa.localizacion_fin_nombre}</span>
          </div>
          <span className="badge px-3 py-2 text-success fw-bold" style={{ background: 'var(--crema-oscura)', borderRadius: 'var(--radius-full)', fontSize: '0.9rem' }}>
            {etapa.distancia} km
          </span>
        </div>
      </div>
    </div>
  );
}