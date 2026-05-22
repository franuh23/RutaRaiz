import React from 'react';
import Badge from '../ui/Badge'; // 👈 Importamos tu componente estrella de la carpeta UI

export default function StatsGeneralSection({ rutas = [], totalLocalizaciones = 0, totalAlojamientos = 0 }) {
  
  const sumaKilometros = rutas.reduce((acc, ruta) => {
    return acc + (Number(ruta.kilometros) || 0);
  }, 0);

  const kilometrosTotales = Math.round(sumaKilometros);

  // Mapeamos metiendo tus componentes Badge reales en el campo 'valor'
  const metricas = [
    { 
      valor: (
        <Badge variant="gold-solid" className="fs-5 px-3 py-2 my-1 shadow-sm" style={{ letterSpacing: '0' }}>
          {kilometrosTotales.toLocaleString('es-ES')} KM
        </Badge>
      ), 
      label: 'En la Red de Caminos', 
      icono: '🥾' 
    },
    { 
      valor: (
        <Badge variant="default" className="fs-5 px-3 py-2 my-1" style={{ letterSpacing: '0' }}>
          {totalLocalizaciones.toLocaleString('es-ES')}
        </Badge>
      ), 
      label: 'Puntos de Paso Reales', 
      icono: '📍' 
    },
    { 
      valor: (
        <Badge variant="difficulty-medium" className="fs-5 px-3 py-2 my-1" style={{ letterSpacing: '0' }}>
          {totalAlojamientos.toLocaleString('es-ES')}
        </Badge>
      ), 
      label: 'Albergues y Hospedajes', 
      icono: '🏠' 
    }
  ];

  return (
    <section className="py-4 my-2 bg-white rounded shadow-sm border border-light-subtle" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="container">
        <div className="row g-3 justify-content-center align-items-center text-center">
          {metricas.map((m, idx) => (
            <div key={m.label} className="col-12 col-md-4 position-relative">
              <div className="d-flex flex-column align-items-center justify-content-center p-2">
                <span className="fs-3 mb-2" role="img" aria-label={m.label}>
                  {m.icono}
                </span>
                
                {/* Aquí se renderiza dinámicamente tu Badge personalizado */}
                <div className="mb-2">
                  {m.valor}
                </div>

                <span 
                  className="text-uppercase tracking-wider text-muted mt-1 fw-semibold" 
                  style={{ fontSize: '0.72rem', letterSpacing: '0.05em' }}
                >
                  {m.label}
                </span>
              </div>
              
              {/* Separador vertical */}
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