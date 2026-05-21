import React from 'react';

export default function AdminRutaRow({ ruta, onEditar, onEliminar }) {
  return (
    <tr>
      <td className="fw-bold text-dark align-middle">#{ruta.id}</td>
      <td className="align-middle fw-semibold" style={{ color: 'var(--verde-bosque)' }}>{ruta.nombre}</td>
      <td className="align-middle text-muted small text-truncate" style={{ maxWidth: '250px' }}>{ruta.descripcion}</td>
      <td className="align-middle"><span className="badge bg-secondary">{ruta.dificultad}</span></td>
      <td className="align-middle fw-bold">{ruta.distancia_total} km</td>
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