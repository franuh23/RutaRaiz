import { useState } from 'react';
import Container from './Container';
import Button from '../ui/Button';
import { NAV_LINKS } from '../../data';
import styles from './Navbar.module.css';

export default function Navbar() {
  const [menuOpen, setMenuOpen] = useState(false);

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
            <Button variant="outline" size="sm">Iniciar sesión</Button>
            <Button variant="primary" size="sm">Registrarse</Button>
          </div>

          {/* Hamburger móvil */}
          <button
            className={styles.hamburger}
            onClick={() => setMenuOpen(!menuOpen)}
            aria-label="Abrir menú"
          >
            <span className={menuOpen ? styles.barOpen : styles.bar} />
            <span className={menuOpen ? styles.barOpen : styles.bar} />
            <span className={menuOpen ? styles.barOpen : styles.bar} />
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
              <Button variant="outline" size="sm">Iniciar sesión</Button>
              <Button variant="primary" size="sm">Registrarse</Button>
            </div>
          </div>
        )}
      </Container>
    </header>
  );
}
