import React from 'react';

export default function ResumenPlanificacion({ planificacion }) {
  const items = [
    { icono: '📅', label: 'Fecha de inicio', valor: planificacion.fecha_inicio },
    { icono: '👣', label: 'Kilómetros por día', valor: `${planificacion.km_dia} km` },
    { icono: '🗓️', label: 'Días totales', valor: `${planificacion.dias_totales} días` },
    { icono: '📍', label: 'Recorrido', valor: `${planificacion.localizacion_inicio_nombre} → ${planificacion.localizacion_fin_nombre || 'Final de ruta'}` }
  ];

  return (
    <div className="row g-3 mb-4">
      {items.map((item, index) => (
        <div key={index} className="col-12 col-sm-6 col-lg-3">
          <div className="card shadow-sm border-0 h-100 p-3 d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-lg)' }}>
            <span className="fs-3">{item.icono}</span>
            <div>
              <span className="d-block text-uppercase text-muted fw-bold" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
                {item.label}
              </span>
              <span className="h6 mb-0 fw-bold" style={{ color: 'var(--verde-bosque)' }}>
                {item.valor}
              </span>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}