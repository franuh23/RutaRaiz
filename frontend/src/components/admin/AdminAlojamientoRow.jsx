import React from 'react';
// Representa una fila individual en la tabla de administración de alojamientos. 
// Recibe el objeto alojamiento y los métodos onEditar y onEliminar para renderizar los datos formateados y despachar las acciones al componente padre.

export default function AdminAlojamientoRow({ alojamiento, onEditar, onEliminar }) {
  
  const getTipoBadge = (tipo) => {
    const t = tipo ? tipo.toLowerCase() : 'albergue';
    switch (t) {
      case 'albergue': return 'bg-success text-white';
      case 'hotel': return 'bg-primary text-white';
      case 'hostal': return 'bg-info text-dark';
      case 'casa_rural': return 'bg-warning text-dark';
      default: return 'bg-secondary text-white';
    }
  };

  return (
    <tr>
      {/* ID */}
      <td className="fw-bold text-dark align-middle ps-3">#{alojamiento.id}</td>
      
      {/* Nombre Comercial */}
      <td className="align-middle fw-bold" style={{ color: 'var(--tierra)' }}>
        {alojamiento.nombre}
      </td>
      
      {/* Tipo con Badge */}
      <td className="align-middle">
        <span className={`badge text-uppercase ${getTipoBadge(alojamiento.tipo)}`} style={{ fontSize: '10px' }}>
          {alojamiento.tipo || 'albergue'}
        </span>
      </td>

      {/* Contacto Rápido (Teléfono / Email) */}
      <td className="align-middle small">
        {alojamiento.telefono && (
          <div className="text-dark fw-medium">
            📞 <a href={`tel:${alojamiento.telefono}`} className="text-decoration-none text-dark">{alojamiento.telefono}</a>
          </div>
        )}
        {alojamiento.email && (
          <div className="text-muted text-truncate" style={{ maxWidth: '160px' }}>
            ✉️ <a href={`mailto:${alojamiento.email}`} className="text-decoration-none text-muted">{alojamiento.email}</a>
          </div>
        )}
        {!alojamiento.telefono && !alojamiento.email && <span className="text-muted fst-italic">Sin datos de contacto</span>}
      </td>

      {/* Dirección o Enlace Web */}
      <td className="align-middle small text-muted">
        <div className="text-truncate fw-medium text-dark" style={{ maxWidth: '180px' }}>
          {alojamiento.direccion || <span className="text-muted fst-italic">Sin dirección física</span>}
        </div>
        {alojamiento.enlace && (
          <a href={alojamiento.enlace} target="_blank" rel="noreferrer" className="text-success fw-bold text-decoration-none small">
            🔗 Ver sitio web / Booking
          </a>
        )}
      </td>
      
      {/* Acciones */}
      <td className="align-middle text-end pe-3">
        <div className="d-inline-flex gap-2">
          <button 
            className="btn btn-sm btn-outline-primary fw-semibold px-3" 
            onClick={() => onEditar(alojamiento)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Editar
          </button>
          <button 
            className="btn btn-sm btn-outline-danger fw-semibold px-3" 
            onClick={() => onEliminar(alojamiento.id)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Eliminar
          </button>
        </div>
      </td>
    </tr>
  );
}