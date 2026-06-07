import React from 'react';
import styles from './Badge.module.css';

export default function Badge({ children, variant = 'default', className = '' }) {
  const classNames = `${styles.badge} ${styles[variant] || styles.default} ${className}`;

  return (
    <span className={classNames.trim()}>
      {children}
    </span>
  );
}