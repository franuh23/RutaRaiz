import Container from './Container';
import { FOOTER_COLS } from '../../data';
import styles from './Footer.module.css';

export default function Footer() {
  return (
    <footer className={styles.footer}>
      <Container>
        <div className={styles.grid}>
          {FOOTER_COLS.map((col) => (
            <div key={col.titulo} className={styles.col}>
              <h4 className={styles.colTitle}>{col.titulo}</h4>
              {col.links.map((link) => (
                <a key={link.label} href={link.href} className={styles.colLink}>
                  {link.label}
                </a>
              ))}
            </div>
          ))}
        </div>

        <div className={styles.copyright}>
          © 2026 RutaRaíz · Proyecto Intermodular 2º DAW · Francisco Miguel Utrera
        </div>
      </Container>
    </footer>
  );
}
