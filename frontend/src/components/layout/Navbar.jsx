import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import Container from './Container';
import Button from '../ui/Button';
import { NAV_LINKS } from '../../data';
import styles from './Navbar.module.css';

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);
  const { user, logout, isAuthenticated } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  return (
    <header className={styles.header}>
      <Container>
        <nav className={styles.navbar}>

          {/* Logo */}
          <a href="/" className={styles.logo}>
            <div className={styles.logoIcon}>
              <span className={styles.logoEmoji}>🎒</span>
              <span className={styles.logoBadge}>🐚</span>
            </div>
            <span className={styles.logoText}>
              Ruta<span className={styles.logoAccent}>Raíz</span>
            </span>
          </a>

          {/* Links escritorio */}
          <div className={styles.navLinks}>
            {NAV_LINKS.map((link) => (
              <a key={link.href} href={link.href} className={styles.navLink}>
                {link.label}
              </a>
            ))}
          </div>

          {/* Botones escritorio */}
          <div className={styles.navActions}>
            {isAuthenticated ? (
              <>
                <span className={styles.userNick}>👋 {user?.nick}</span>
                <Button variant="outline" size="sm" onClick={handleLogout}>
                  Cerrar sesión
                </Button>
              </>
            ) : (
              <>
                <Button variant="outline" size="sm" onClick={() => navigate('/login')}>
                  Iniciar sesión
                </Button>
                <Button variant="primary" size="sm" onClick={() => navigate('/register')}>
                  Registrarse
                </Button>
              </>
            )}
          </div>

          {/* Hamburger móvil */}
          <button
            className={styles.hamburger}
            onClick={() => setMenuOpen(!menuOpen)}
            aria-label="Abrir menú"
          >
            <span className={styles.bar} />
            <span className={styles.bar} />
            <span className={styles.bar} />
          </button>
        </nav>

        {/* Menú móvil desplegable */}
        {menuOpen && (
          <div className={styles.mobileMenu}>
            {NAV_LINKS.map((link) => (
              <a key={link.href} href={link.href} className={styles.mobileLink}>
                {link.label}
              </a>
            ))}
            <div className={styles.mobileActions}>
              {isAuthenticated ? (
                <Button variant="outline" size="sm" onClick={handleLogout}>
                  Cerrar sesión
                </Button>
              ) : (
                <>
                  <Button variant="outline" size="sm" onClick={() => navigate('/login')}>
                    Iniciar sesión
                  </Button>
                  <Button variant="primary" size="sm" onClick={() => navigate('/register')}>
                    Registrarse
                  </Button>
                </>
              )}
            </div>
          </div>
        )}

      </Container>
    </header>
  );
}