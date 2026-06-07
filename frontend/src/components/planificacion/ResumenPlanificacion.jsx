import React from 'react';
import Badge from '../ui/Badge';
// Muestra tarjetas de una planificacion

export default function ResumenPlanificacion({ planificacion }) {
  const { fecha_inicio, km_dia = 0, dias_totales = 0, etapas = [] } = planificacion || {};

  const fechaEs = fecha_inicio 
    ? new Date(fecha_inicio).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }) 
    : '-';
  
  const puntoInicio = etapas.length > 0 ? etapas[0].inicio : 'Inicio';
  const puntoFin = etapas.length > 0 ? etapas[etapas.length - 1].fin : 'Final';

  const items = [
    { icono: '📅', label: 'Fecha de inicio', valor: fechaEs, tag: 'default' },
    { icono: '👣', label: 'Marcha diaria', valor: `${km_dia} km/día`, tag: 'default' },
    { icono: '🗓️', label: 'Días de viaje', valor: `${dias_totales} ${dias_totales === 1 ? 'día' : 'días'}`, tag: 'difficulty-medium' },
    { icono: '📍', label: 'Itinerario', valor: `${puntoInicio} → ${puntoFin}`, tag: 'gold-solid' }
  ];

  return (
    <div className="row g-3 mb-4">
      {items.map((item) => (
        <div key={item.label} className="col-12 col-sm-6 col-lg-3">
          <div className="card shadow-sm border-0 h-100 p-3 d-flex flex-row align-items-center gap-3 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
            <span className="fs-2">{item.icono}</span>
            <div className="flex-grow-1">
              <span className="d-block text-uppercase text-muted fw-bold mb-1" style={{ fontSize: '0.72rem', letterSpacing: '0.05em' }}>
                {item.label}
              </span>
              <div className="mt-1">
                <Badge variant={item.tag} size="sm" style={{ letterSpacing: '0' }} className="fw-bold fs-7">
                  {item.valor}
                </Badge>
              </div>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}