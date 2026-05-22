import React from 'react';
import { useNavigate } from 'react-router-dom';
import Badge from '../ui/Badge';
import Button from '../ui/Button';
import Container from '../layout/Container';
import styles from './HeroSection.module.css';

export default function HeroSection({ onPlanificar }) {
  const navigate = useNavigate();

  return (
    <section className={styles.hero}>
      {/* Decoración de fondo */}
      <div className={styles.bgDeco} aria-hidden="true" />

      <Container>
        <div className={styles.content}>
          <Badge variant="gold">🥾 Planificador Oficial</Badge>

          <h1 className={styles.titulo}>
            Planifica tu <span className={styles.accent}>aventura</span> por etapas
          </h1>

          <p className={styles.subtitulo}>
            Introduce tus días disponibles, ritmo y destino.
            Te generamos la ruta perfecta adaptada a ti.
          </p>

          <div className={styles.botones}>
            <button
              className="btn btn-success px-4 py-2 fw-bold text-white shadow-sm"
              onClick={onPlanificar}
              style={{ borderRadius: 'var(--radius-md)', background: 'var(--verde-medio)', border: 'none' }}
            >
              🎒 Comenzar mi ruta
            </button>
            
            <Button
              variant="outline"
              size="md"
              onClick={() => navigate('/rutas')}
            >
              Explorar rutas
            </Button>
          </div>

          {/* Stats rápidos */}
          <div className={styles.stats}>
            {[
              { valor: 'Primitivo', label: 'Camino Estrella' },
              { valor: '100%', label: 'Albergues Reales' },
              { valor: 'A medida', label: 'Etapas Diarias' },
            ].map((s) => (
              <div key={s.label} className={styles.stat}>
                <span className={styles.statValor}>{s.valor}</span>
                <span className={styles.statLabel}>{s.label}</span>
              </div>
            ))}
          </div>
        </div>
      </Container>
    </section>
  );
}