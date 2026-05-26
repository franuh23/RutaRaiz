import React from 'react';

export default function AdminUsuarioRow({ usuario, onCambiarRol, onToggleActivo, onEliminar }) {
  return (
    <tr className={!usuario.activo ? 'table-danger-subtle text-muted' : ''}>
      {/* ID */}
      <td className="fw-bold align-middle ps-3">#{usuario.id}</td>
      
      {/* Avatar + Nick */}
      <td className="align-middle fw-bold">
        <div className="d-flex align-items-center gap-2">
          <div 
            className="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
            style={{ width: '32px', height: '32px', fontSize: '12px', overflow: 'hidden' }}
          >
            {usuario.avatar_url ? (
              <img src={usuario.avatar_url} alt={usuario.nick} className="w-100 h-100 object-fit-cover" />
            ) : (
              usuario.nick.substring(0, 2).toUpperCase()
            )}
          </div>
          <span style={{ color: 'var(--verde-bosque)' }}>{usuario.nick}</span>
        </div>
      </td>

      {/* Nombre y Apellidos */}
      <td className="align-middle">
        <div className="fw-semibold text-dark">{usuario.nombre}</div>
        <div className="text-muted small">{usuario.apellidos}</div>
      </td>

      {/* Email */}
      <td className="align-middle small font-monospace">{usuario.email}</td>

      {/* Rol Selector */}
      <td className="align-middle">
        <select 
          className="form-select form-select-sm fw-bold border-0 bg-light" 
          value={usuario.rol}
          onChange={(e) => onCambiarRol(usuario.id, e.target.value)}
          style={{ width: '110px', borderRadius: 'var(--radius-sm)' }}
        >
          <option value="usuario">👤 Usuario</option>
          <option value="admin">👑 Admin</option>
        </select>
      </td>

      {/* Estado Activo (Interruptor Rápido) */}
      <td className="align-middle">
        <button 
          className={`btn btn-sm fw-bold px-3 py-1 ${usuario.activo ? 'btn-light text-success border-success-subtle' : 'btn-danger'}`}
          onClick={() => onToggleActivo(usuario.id, !usuario.activo)}
          style={{ borderRadius: 'var(--radius-md)', fontSize: '11px' }}
        >
          {usuario.activo ? '🟢 ACTIVO' : '🔴 BANEADO'}
        </button>
      </td>

      {/* Acciones (Borrado total) */}
      <td className="align-middle text-end pe-3">
        <button 
          className="btn btn-sm btn-outline-danger fw-semibold px-2" 
          onClick={() => onEliminar(usuario.id)}
          style={{ borderRadius: 'var(--radius-md)' }}
        >
          Eliminar
        </button>
      </td>
    </tr>
  );
}