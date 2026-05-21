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
    <header className="navbar navbar-expand-md navbar-light bg-white sticky-top shadow-sm py-3">
      <div className="container">
        {/* Logo - solo imagen + texto */}
        <Link to="/" className="navbar-brand d-flex align-items-center gap-2">
          <img src="/logoRutaRaiz.png" alt="RutaRaíz" height="40" />
          <span className="fw-bold text-dark fs-4">
            Ruta<span style={{ color: 'var(--verde-medio)' }}>Raíz</span>
          </span>
        </Link>

        {/* Botón hamburguesa */}
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
            {isAuthenticated && user?.rol === 'admin' && (
              <li className="nav-item">
                <Link to="/admin" className="nav-link text-danger fw-bold">Panel Admin</Link>
              </li>
            )}
          </ul>

          {/* Acciones de usuario a la derecha */}
          <div className="d-flex align-items-center gap-3 ms-md-3">
            {isAuthenticated ? (
              <>
                <Link to="/perfil" className="text-dark text-decoration-none">
                  👋 {user?.nick} <span className="badge bg-secondary">{user?.rol}</span>
                </Link>
                <button className="btn btn-sm btn-outline-danger" onClick={handleLogout}>
                  Cerrar sesión
                </button>
              </>
            ) : (
              <>
                <button className="btn btn-sm btn-outline-success" onClick={() => navigate('/login')}>
                  Iniciar sesión
                </button>
                <button className="btn btn-sm text-white" style={{ background: 'var(--verde-bosque)' }} onClick={() => navigate('/register')}>
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