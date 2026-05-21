import Container from '../layout/Container';
import StepCard from '../ui/StepCard';
import { PASOS } from '../../data';
import styles from './HowItWorksSection.module.css';

export default function HowItWorksSection() {
  return (
    <section className={styles.section}>
      <Container>
        <div className={styles.header}>
          <h2 className={styles.titulo}>Cómo funciona RutaRaíz</h2>
          <p className={styles.sub}>
            Diseñado para que preparar tu ruta de varios días sea sencillo y rápido
          </p>
        </div>

        <div className={styles.steps}>
          {PASOS.map((paso, i) => (
            <StepCard
              key={paso.numero}
              paso={paso}
              isLast={i === PASOS.length - 1}
            />
          ))}
        </div>
      </Container>
    </section>
  );
}
