import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);
  const { user, logout, isAuthenticated } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  // Generamos el avatar por defecto con las iniciales si el usuario no tiene foto subida
  const avatarPorDefecto = `https://api.dicebear.com/7.x/initials/svg?seed=${user?.nick || 'Peregrino'}&backgroundType=gradientLinear&backgroundColor=4e6e58,2d4a34`;

  return (
    <header className="navbar navbar-expand-md navbar-light bg-white sticky-top shadow-sm py-3">
      <div className="container">
        {/* Logo - solo imagen + texto */}
        <Link to="/" className="navbar-brand d-flex align-items-center gap-2">
          <img src="/logoRutaRaiz.png" alt="RutaRaíz" height="40" />
          <span className="fw-bold text-dark fs-4">
            Ruta<span style={{ color: 'var(--verde-medio)' }}>Raíz</span>
          </span>
        </Link>

        {/* Botón hamburguesa para móviles */}
        <button
          className="navbar-toggler"
          type="button"
          onClick={() => setMenuOpen(!menuOpen)}
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        {/* Menú colapsable */}
        <div className={`collapse navbar-collapse ${menuOpen ? 'show' : ''}`}>
          <ul className="navbar-nav ms-auto mb-2 mb-md-0 gap-3">
            <li className="nav-item">
              <Link to="/rutas" className="nav-link">Rutas</Link>
            </li>
            <li className="nav-item">
              <Link to="/planificador" className="nav-link">Planificador</Link>
            </li>
            {isAuthenticated && (
              <li className="nav-item">
                <Link to="/mis-planificaciones" className="nav-link">Mis Planificaciones</Link>
              </li>
            )}
            {/* 🎒 NUEVA PESTAÑA: Comunidad accesible para usuarios logueados */}
            {isAuthenticated && (
              <li className="nav-item">
                <Link to="/comunidad" className="nav-link" style={{ fontWeight: '600' }}>Comunidad</Link>
              </li>
            )}
            {isAuthenticated && user?.rol === 'admin' && (
              <li className="nav-item">
                <Link to="/admin" className="nav-link text-danger fw-bold">Panel Admin</Link>
              </li>
            )}
          </ul>

          {/* Acciones de usuario a la derecha */}
          <div className="d-flex align-items-center gap-3 ms-md-3 mt-3 mt-md-0">
            {isAuthenticated ? (
              <>
                {/* Enlace al perfil con la foto redonda y el Nick, sin el rol */}
                <Link to="/perfil" className="text-dark text-decoration-none d-flex align-items-center gap-2 fw-semibold">
                  <img 
                    src={user?.avatar_url || avatarPorDefecto} 
                    alt={`Avatar de ${user?.nick}`}
                    className="rounded-circle border"
                    style={{ width: '32px', height: '32px', objectFit: 'cover' }}
                  />
                  <span>{user?.nick}</span>
                </Link>
                <button className="btn btn-sm btn-outline-danger" onClick={handleLogout} style={{ borderRadius: 'var(--radius-md)' }}>
                  Cerrar sesión
                </button>
              </>
            ) : (
              <>
                <button className="btn btn-sm btn-outline-success" onClick={() => navigate('/login')} style={{ borderRadius: 'var(--radius-md)' }}>
                  Iniciar sesión
                </button>
                <button className="btn btn-sm text-white" style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }} onClick={() => navigate('/register')}>
                  Registrarse
                </button>
              </>
            )}
          </div>
        </div>
      </div>
    </header>
  );
}