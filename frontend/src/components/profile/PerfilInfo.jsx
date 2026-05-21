import React from 'react';

export default function PerfilInfo({ user }) {
  return (
    <div className="card shadow-sm border-0 p-4 h-100" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h3 className="h5 fw-bold mb-4" style={{ color: 'var(--verde-bosque)' }}>
        🎒 Datos de tu Cuenta
      </h3>
      
      <div className="mb-3 border-bottom pb-2">
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
          Nombre de usuario (Nick)
        </label>
        <span className="fw-semibold text-dark fs-5">{user?.nick}</span>
      </div>

      <div className="mb-3 border-bottom pb-2">
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
          Nombre Completo
        </label>
        <span className="text-dark">{user?.nombre} {user?.apellidos}</span>
      </div>

      <div className="mb-3 border-bottom pb-2">
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
          Correo Electrónico
        </label>
        <span className="text-dark">{user?.email}</span>
      </div>

      <div>
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.75rem', letterSpacing: '0.05em' }}>
          Rol asignado
        </label>
        <span className="badge bg-secondary text-capitalize mt-1 px-3 py-1.5">{user?.rol}</span>
      </div>
    </div>
  );
}