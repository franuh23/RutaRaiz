import styles from './Badge.module.css';

/**
 * Badge - etiqueta pequeña de estado o categoría
 * @param {'default'|'gold'|'difficulty-easy'|'difficulty-medium'|'difficulty-hard'} variant
 */
export default function Badge({ children, variant = 'default', className = '' }) {
  return (
    <span className={[styles.badge, styles[variant], className].join(' ')}>
      {children}
    </span>
  );
}
