import React from 'react';
import Container from './Container';
import styles from './Footer.module.css';

export default function Footer() {
  return (
    <footer className={styles.footer}>
      <Container>
        <div className={styles.copyright}>
          © 2026 RutaRaíz · Proyecto Intermodular 2º DAW · Francisco Miguel Utrera
        </div>
      </Container>
    </footer>
  );
}