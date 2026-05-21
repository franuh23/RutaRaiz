import Container from '../layout/Container';
import FeatureCard from '../ui/FeatureCard';
import { FEATURES } from '../../data';
import styles from './FeaturesSection.module.css';

export default function FeaturesSection() {
  return (
    <section className={styles.section}>
      <Container>
        <div className={styles.header}>
          <h2 className={styles.titulo}>Todo lo que necesitas</h2>
          <p className={styles.sub}>
            Herramientas pensadas para senderistas, peregrinos y amantes de la naturaleza
          </p>
        </div>

        <div className={styles.grid}>
          {FEATURES.map((feature) => (
            <FeatureCard key={feature.id} feature={feature} />
          ))}
        </div>
      </Container>
    </section>
  );
}
