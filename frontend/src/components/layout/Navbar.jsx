import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import Container from './Container';

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);
  const { user, logout, isAuthenticated } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  return (
    <header className="navbar navbar-expand-md navbar-light bg-white shadow-sm py-3">
      <Container>
        {/* Logo */}
        <Link to="/" className="navbar-brand d-flex align-items-center fw-bold text-dark fs-4">
          <span className="me-2">🎒</span>
          <span>Ruta<span style={{ color: 'var(--verde-medio)' }}>Raíz</span></span>
        </Link>

        {/* Hamburger móvil */}
        <button 
          className="navbar-toggler border-0" 
          type="button" 
          onClick={() => setMenuOpen(!menuOpen)}
          aria-label="Menu"
        >
          <span className="navbar-toggler-icon"></span>
        </button>

        {/* Menú de navegación */}
        <div className={`collapse navbar-collapse ${menuOpen ? 'show' : ''}`}>
          <ul className="navbar-nav me-auto mb-2 mb-md-0 ms-md-4 gap-2">
            <li className="nav-item">
              <Link to="/rutas" className="nav-link fw-semibold text-secondary">Rutas</Link>
            </li>
            <li className="nav-item">
              <Link to="/planificador" className="nav-link fw-semibold text-secondary">Planificador</Link>
            </li>
            {isAuthenticated && (
              <li className="nav-item">
                <Link to="/mis-planificaciones" className="nav-link fw-semibold text-secondary">
                  Mis Planificaciones
                </Link>
              </li>
            )}
            {/* Enlace dinámico exclusivo para administradores */}
            {isAuthenticated && user?.rol === 'admin' && (
              <li className="nav-item">
                <Link to="/admin" className="nav-link fw-bold text-danger">
                  Panel Admin
                </Link>
              </li>
            )}
          </ul>

          {/* Acciones de usuario */}
          <div className="d-flex align-items-center gap-3">
            {isAuthenticated ? (
              <>
                <span className="fw-bold text-dark small">
                  👋 {user?.nick} <span className="badge bg-secondary ms-1 text-capitalize">{user?.rol}</span>
                </span>
                <button 
                  className="btn btn-sm btn-outline-danger px-3 fw-bold" 
                  onClick={handleLogout}
                  style={{ borderRadius: 'var(--radius-md)' }}
                >
                  Cerrar sesión
                </button>
              </>
            ) : (
              <>
                <button 
                  className="btn btn-sm btn-outline-success px-3 fw-bold" 
                  onClick={() => navigate('/login')}
                  style={{ borderRadius: 'var(--radius-md)' }}
                >
                  Iniciar sesión
                </button>
                <button 
                  className="btn btn-sm text-white px-3 fw-bold" 
                  onClick={() => navigate('/register')}
                  style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}
                >
                  Registrarse
                </button>
              </>
            )}
          </div>
        </div>
      </Container>
    </header>
  );
}