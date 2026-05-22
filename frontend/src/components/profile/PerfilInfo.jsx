import React from 'react';

export default function PerfilInfo({ user }) {
  // Avatar por defecto si no tiene una foto subida
  const avatarPorDefecto = `https://api.dicebear.com/7.x/initials/svg?seed=${user?.nick || 'Peregrino'}&backgroundType=gradientLinear&backgroundColor=4e6e58,2d4a34`;

  return (
    <div className="card shadow-sm border-0 p-4 h-100 text-center text-md-start" style={{ borderRadius: 'var(--radius-lg)' }}>
      {/* Bloque visual del Avatar */}
      <div className="d-flex flex-column align-items-center mb-4 text-center">
        <img 
          src={user?.avatar_url || avatarPorDefecto} 
          alt={`Avatar de ${user?.nick}`}
          className="rounded-circle shadow-sm border border-2 border-light mb-2"
          style={{ width: '100px', height: '100px', objectFit: 'cover' }}
        />
        <h4 className="m-0 h6 text-muted">@{user?.nick}</h4>
      </div>

      <h3 className="h6 text-uppercase text-muted fw-bold mb-3 tracking-wider" style={{ fontSize: '0.75rem' }}>
        🎒 Credenciales de ruta
      </h3>
      
      <div className="mb-3 border-bottom pb-2 text-start">
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.7rem', letterSpacing: '0.05em' }}>
          Nombre Completo
        </label>
        <span className="fw-semibold text-dark">
          {user?.nombre || user?.name} {user?.apellidos || ''}
        </span>
      </div>

      <div className="mb-3 border-bottom pb-2 text-start">
        <label className="text-uppercase text-muted fw-bold d-block" style={{ fontSize: '0.7rem', letterSpacing: '0.05em' }}>
          Correo Electrónico
        </label>
        <span className="text-dark small">{user?.email}</span>
      </div>
    </div>
  );
}