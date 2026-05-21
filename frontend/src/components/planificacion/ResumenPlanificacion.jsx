import React from 'react';

export default function ResumenPlanificacion({ planificacion }) {
  const fechaEs = planificacion?.fecha_inicio 
    ? new Date(planificacion.fecha_inicio).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }) 
    : '-';
  
  const etapas = planificacion?.etapas || [];
  const puntoInicio = etapas.length > 0 ? etapas[0].inicio : 'Inicio';
  const puntoFin = etapas.length > 0 ? etapas[etapas.length - 1].fin : 'Final';

  const items = [
    { icono: '📅', label: 'Fecha de inicio', valor: fechaEs },
    { icono: '👣', label: 'Kilómetros por día', valor: `${planificacion?.km_dia} km` },
    { icono: '🗓️', label: 'Días totales', valor: `${planificacion?.dias_totales} días` },
    { icono: '📍', label: 'Recorrido', valor: `${puntoInicio} → ${puntoFin}` }
  ];

  return (
    <div className="row g-3 mb-4">
      {items.map((item, index) => (
        <div key={index} className="col-12 col-sm-6 col-lg-3">
          <div className="card shadow-sm border-0 h-100 p-3 d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-lg)', backgroundColor: '#fff' }}>
            <span className="fs-3">{item.icono}</span>
            <div>
              <span className="d-block text-uppercase text-muted fw-bold" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>{item.label}</span>
              <span className="h6 mb-0 fw-bold text-dark">{item.valor}</span>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}