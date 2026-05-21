import React from 'react';

export default function AlojamientoFicha({ alojamiento }) {
  return (
    <div className="card border-0 shadow-sm p-4 mb-4 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h1 className="mb-4 fw-bold text-dark" style={{ fontFamily: 'var(--font-display)', fontSize: '2.5rem' }}>
        {alojamiento.nombre}
      </h1>

      <div className="d-flex flex-column gap-3 fs-5 text-dark">
        {alojamiento.localizacion?.nombre && (
          <div>
            <strong className="text-dark">Localización:</strong> {alojamiento.localizacion.nombre}
          </div>
        )}
        
        {alojamiento.direccion && (
          <div>
            <strong className="text-dark">Dirección:</strong> {alojamiento.direccion}
          </div>
        )}

        <div>
          <strong className="text-dark">Tipo:</strong> <span className="text-lowercase">{alojamiento.tipo}</span>
        </div>

        {alojamiento.enlace && (
          <div>
            <strong className="text-dark">Enlace web:</strong>{' '}
            <a 
              href={alojamiento.enlace} 
              target="_blank" 
              rel="noopener noreferrer" 
              className="text-primary text-decoration-underline"
            >
              {alojamiento.enlace}
            </a>
          </div>
        )}

        {alojamiento.telefono && (
          <div>
            <strong className="text-dark">Teléfono:</strong> {alojamiento.telefono}
          </div>
        )}

        {alojamiento.email && (
          <div>
            <strong className="text-dark">Email:</strong> {alojamiento.email}
          </div>
        )}

      </div>
    </div>
  );
}