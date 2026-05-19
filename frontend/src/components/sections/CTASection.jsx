import Container from '../layout/Container';
import Button from '../ui/Button';
import styles from './CTASection.module.css';

export default function CTASection() {
  return (
    <section className={styles.section}>
      <Container>
        <div className={styles.card}>
          <div className={styles.deco} aria-hidden="true">🌿</div>
          <h2 className={styles.titulo}>¿Listo para tu próxima aventura?</h2>
          <p className={styles.sub}>
            Únete a miles de senderistas que ya planifican sus rutas con RutaRaíz.
          </p>
          <Button variant="primary" size="lg">
            Crear cuenta gratuita →
          </Button>
        </div>
      </Container>
    </section>
  );
}
