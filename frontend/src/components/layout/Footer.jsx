import React from 'react';
import Container from './Container';

export default function Footer() {
  return (
    <footer className="text-white-50 small py-3" style={{ backgroundColor: 'var(--verde-bosque)' }}>
      <Container>
        <div className="text-center fw-medium" style={{ letterSpacing: '0.02em' }}>
          © 2026 RutaRaíz · Proyecto Intermodular 2º DAW · Francisco Miguel Utrera
        </div>
      </Container>
    </footer>
  );
}