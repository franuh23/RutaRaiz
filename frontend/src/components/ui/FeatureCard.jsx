import React from 'react';
import styles from './FeatureCard.module.css';
// Tarjeta de características destacadas.

export default function FeatureCard({ feature }) {
  const { icono = '📍', titulo = 'Característica', descripcion = '' } = feature || {};

  return (
    <div className={styles.card}>
      <div className={styles.iconWrapper}>
        <span className={styles.icon} role="img" aria-label={titulo}>
          {icono}
        </span>
      </div>
      <h3 className={styles.titulo}>{titulo}</h3>
      <p className={styles.desc}>{descripcion}</p>
    </div>
  );
}