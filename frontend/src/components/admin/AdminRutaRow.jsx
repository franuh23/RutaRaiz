import React from 'react';

export default function AdminRutaRow({ ruta, onEditar, onEliminar }) {
  
  // Helper adaptado a los valores string en minúscula del backend
  const getDificultadBadge = (dificultad) => {
    const diff = dificultad ? dificultad.toLowerCase() : 'media';
    switch (diff) {
      case 'baja': return 'bg-success-subtle text-success border border-success-subtle';
      case 'alta': return 'bg-danger-subtle text-danger border border-danger-subtle';
      default: return 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'; // media
    }
  };

  return (
    <tr>
      <td className="fw-bold text-dark align-middle">#{ruta.id}</td>
      
      <td className="align-middle fw-bold" style={{ color: 'var(--verde-bosque)' }}>
        {ruta.nombre}
      </td>
      
      <td className="align-middle text-secondary small">
        <span className="fw-medium text-dark">{ruta.inicio || 'Inicio'}</span> 
        <span className="mx-1 text-success">→</span> 
        <span className="fw-medium text-dark">{ruta.fin || 'Fin'}</span>
      </td>
      
      <td className="align-middle fw-bold text-dark">
        {ruta.kilometros ? `${ruta.kilometros} km` : '0 km'}
      </td>

      <td className="align-middle">
        <span className={`badge px-2 py-1 text-uppercase ${getDificultadBadge(ruta.dificultad)}`} style={{ fontSize: '11px', letterSpacing: '0.03em' }}>
          {ruta.dificultad || 'media'}
        </span>
      </td>

      <td className="align-middle text-muted small text-truncate" style={{ maxWidth: '200px' }}>
        {ruta.descripcion || <span className="text-muted fst-italic">Sin descripción</span>}
      </td>
      
      <td className="align-middle text-end">
        <div className="d-inline-flex gap-2">
          <button 
            className="btn btn-sm btn-outline-primary fw-semibold px-3" 
            onClick={() => onEditar(ruta)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Editar
          </button>
          <button 
            className="btn btn-sm btn-outline-danger fw-semibold px-3" 
            onClick={() => onEliminar(ruta.id)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Eliminar
          </button>
        </div>
      </td>
    </tr>
  );
}