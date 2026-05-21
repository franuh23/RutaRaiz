import React from 'react';

export default function AdminLocalizacionRow({ loc, onEditar, onEliminar }) {
  return (
    <tr>
      <td className="fw-bold text-dark align-middle">#{loc.id}</td>
      <td className="align-middle fw-semibold" style={{ color: 'var(--verde-medio)' }}>{loc.nombre}</td>
      <td className="align-middle text-muted small text-truncate" style={{ maxWidth: '200px' }}>{loc.descripcion}</td>
      <td className="align-middle fw-bold text-secondary">{loc.distancia_desde_inicio} km</td>
      <td className="align-middle text-end">
        <div className="d-inline-flex gap-2">
          <button 
            className="btn btn-sm btn-outline-primary fw-semibold px-3" 
            onClick={() => onEditar(loc)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Editar
          </button>
          <button 
            className="btn btn-sm btn-outline-danger fw-semibold px-3" 
            onClick={() => onEliminar(loc.id)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Eliminar
          </button>
        </div>
      </td>
    </tr>
  );
}