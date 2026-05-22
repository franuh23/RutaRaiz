import React from 'react';

export default function StatsGeneralSection({ rutas, totalLocalizaciones, totalAlojamientos }) {
  // 1. Apuntamos al campo real 'kilometros' y sumamos de forma segura
  const sumaKilometros = rutas.reduce((acc, ruta) => {
    return acc + (Number(ruta.kilometros) || 0);
  }, 0);

  // 2. Redondeamos el número total para fulminar los decimales infinitos de JavaScript
  const kilometrosTotales = Math.round(sumaKilometros);

  const metricas = [
    { valor: `${kilometrosTotales.toLocaleString('es-ES')} km`, label: 'En la Red de Caminos', icono: '🥾' },
    { valor: totalLocalizaciones, label: 'Puntos de Paso Reales', icono: '📍' },
    { valor: totalAlojamientos, label: 'Albergues y Hospedajes', icono: '🏠' }
  ];

  return (
    <section className="py-4 my-2 bg-white rounded shadow-sm border border-light-subtle">
      <div className="container">
        <div className="row g-3 justify-content-center align-items-center text-center">
          {metricas.map((m, idx) => (
            <div key={idx} className="col-12 col-md-4 position-relative">
              <div className="d-flex flex-column align-items-center justify-content-center p-2">
                <span className="fs-3 mb-1" role="img" aria-label={m.label}>
                  {m.icono}
                </span>
                <span 
                  className="fw-extrabold text-dark m-0" 
                  style={{ fontFamily: 'var(--font-display)', fontSize: '1.6rem', fontWeight: '800', lineHeight: '1.2' }}
                >
                  {m.valor}
                </span>
                <span 
                  className="text-uppercase tracking-wider text-muted mt-1 fw-semibold" 
                  style={{ fontSize: '0.72rem', letterSpacing: '0.05em' }}
                >
                  {m.label}
                </span>
              </div>
              {/* Separador vertical estético visible solo en pantallas medianas/grandes */}
              {idx < 2 && (
                <div className="d-none d-md-block position-absolute top-50 end-0 translate-middle-y bg-body-secondary" style={{ width: '1px', height: '50px' }} />
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}