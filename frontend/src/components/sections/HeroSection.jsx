import Button from '../ui/Button';
import Badge from '../ui/Badge';
import Container from '../layout/Container';
import styles from './HeroSection.module.css';

export default function HeroSection() {
  return (
    <section className={styles.hero}>
      {/* Decoración de fondo */}
      <div className={styles.bgDeco} aria-hidden="true" />

      <Container>
        <div className={styles.content}>
          <Badge variant="gold">🥾 +200 rutas GR en España</Badge>

          <h1 className={styles.titulo}>
            Planifica tu{' '}
            <span className={styles.accent}>aventura</span>
            {' '}por etapas
          </h1>

          <p className={styles.subtitulo}>
            Introduce tus días disponibles, ritmo y destino.
            Te generamos la ruta perfecta adaptada a ti.
          </p>

          <div className={styles.botones}>
            <Button variant="primary" size="lg">🎒 Comenzar mi ruta</Button>
            <Button variant="secondary" size="lg">Explorar rutas</Button>
          </div>

          {/* Stats rápidos */}
          <div className={styles.stats}>
            {[
              { valor: '+200', label: 'Rutas GR' },
              { valor: '+12k', label: 'Senderistas' },
              { valor: '17',   label: 'Comunidades autónomas' },
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
