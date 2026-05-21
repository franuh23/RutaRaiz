import React from 'react';

export default function AdminAlojamientoRow({ alojamiento, onEditar, onEliminar }) {
  return (
    <tr>
      <td className="fw-bold text-dark align-middle">#{alojamiento.id}</td>
      <td className="align-middle fw-semibold" style={{ color: 'var(--tierra)' }}>{alojamiento.nombre}</td>
      <td className="align-middle text-muted small">{alojamiento.tipo}</td>
      <td className="align-middle fw-bold" style={{ color: 'var(--verde-medio)' }}>{alojamiento.precio_noche} €</td>
      <td className="align-middle text-secondary small">{alojamiento.plazas_totales} plazas</td>
      <td className="align-middle text-end">
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