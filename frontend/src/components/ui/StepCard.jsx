import styles from './StepCard.module.css';

export default function StepCard({ paso, isLast }) {
  const { numero, titulo, descripcion } = paso;

  return (
    <div className={styles.wrapper}>
      <div className={styles.card}>
        <div className={styles.number}>{numero}</div>
        <h3 className={styles.titulo}>{titulo}</h3>
        <p className={styles.desc}>{descripcion}</p>
      </div>
      {!isLast && <div className={styles.connector} aria-hidden="true">→</div>}
    </div>
  );
}
