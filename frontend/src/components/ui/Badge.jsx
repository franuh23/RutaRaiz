import React from 'react';
import styles from './Badge.module.css';

/**
 * Badge - etiqueta pequeña de estado o categoría
 * @param {'default'|'gold'|'gold-solid'|'difficulty-easy'|'difficulty-medium'|'difficulty-hard'} variant
 */
export default function Badge({ children, variant = 'default', className = '' }) {
  const classNames = `${styles.badge} ${styles[variant] || styles.default} ${className}`;

  return (
    <span className={classNames.trim()}>
      {children}
    </span>
  );
}