import Container from '../layout/Container';
import RouteCard from '../ui/RouteCard';
import Button from '../ui/Button';
import { RUTAS_DESTACADAS } from '../../data';
import styles from './RoutesPreviewSection.module.css';

export default function RoutesPreviewSection() {
  return (
    <section className={styles.section}>
      <Container>
        <div className={styles.header}>
          <div>
            <h2 className={styles.titulo}>Rutas destacadas</h2>
            <p className={styles.sub}>
              Algunas de las rutas de gran recorrido más populares de España
            </p>
          </div>
          <Button variant="ghost">Ver todas las rutas →</Button>
        </div>

        <div className={styles.grid}>
          {RUTAS_DESTACADAS.map((ruta) => (
            <RouteCard key={ruta.id} ruta={ruta} />
          ))}
        </div>
      </Container>
    </section>
  );
}
