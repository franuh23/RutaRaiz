import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import Badge from '../ui/Badge';
import Button from '../ui/Button';

export default function EtapaCard({ etapa }) {
  const [isOpen, setIsOpen] = useState(false);
  const { dia = 1, inicio = 'Inicio', fin = 'Fin', distancia = 0, alojamientos = [] } = etapa || {};
  const tieneAlojamientos = alojamientos.length > 0;

  return (
    <div 
      className="card shadow-sm border-0 mb-3 bg-white" 
      style={{ borderRadius: 'var(--radius-lg)', overflow: 'hidden' }}
    >
      {/* Cabecera de la Etapa */}
      <div 
        className="p-3 d-flex align-items-center justify-content-between flex-wrap gap-3"
        style={{ cursor: tieneAlojamientos ? 'pointer' : 'default', userSelect: 'none' }}
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
            <strong className="fs-4 lh-1 fw-bold">{dia}</strong>
          </div>

          {/* Trayecto Geográfico */}
          <div>
            <div className="d-flex align-items-center gap-2 flex-wrap">
              <span className="fw-bold text-dark fs-5">{inicio}</span>
              <span className="fw-bold fs-5 text-muted opacity-50">→</span>
              <span className="fw-bold fs-5" style={{ color: 'var(--verde-bosque)' }}>{fin}</span>
            </div>
            {tieneAlojamientos ? (
              <small className="text-muted d-block mt-1">
                {isOpen ? '🔼 Ocultar servicios disponibles' : `🔽 Ver ${alojamientos.length} alojamientos en ${fin}`}
              </small>
            ) : (
              <small className="text-muted d-block mt-1 fw-medium text-warning">⚠️ Sin alojamientos registrados en esta parada</small>
            )}
          </div>
        </div>

        {/* Métrica de Distancia utilizando tu Badge */}
        <div className="d-flex align-items-center gap-2">
          <Badge variant="default" className="fs-6 px-3 py-2">
            {distancia} km
          </Badge>
        </div>
      </div>

      {/* Desplegable de Alojamientos Asociados */}
      {tieneAlojamientos && isOpen && (
        <div className="card-body bg-light border-top p-3">
          <h6 className="text-uppercase text-muted fw-bold mb-3" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
            🏡 Dónde dormir en {fin}:
          </h6>
          <div className="row g-2">
            {alojamientos.map((aloj) => (
              <div className="col-12 col-md-6" key={aloj.id}>
                <div 
                  className="card border-0 bg-white p-3 h-100 d-flex flex-row justify-content-between align-items-center shadow-sm"
                  style={{ borderRadius: 'var(--radius-md)' }}
                >
                  <div className="pe-2">
                    <Badge variant="difficulty-medium" size="sm" className="mb-1 text-uppercase">
                      {aloj.tipo || 'Albergue'}
                    </Badge>
                    <h6 className="mb-0 fw-bold text-dark mt-1">{aloj.nombre}</h6>
                  </div>
                  <div className="text-end flex-shrink-0">
                    <Link to={`/alojamientos/${aloj.id}`} className="text-decoration-none">
                      <Button variant="outline" size="sm">
                        Ver detalles
                      </Button>
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