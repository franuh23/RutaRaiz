import React from 'react';
import styles from './Button.module.css';

/**
 * Button - Componente de botón reutilizable y estilizado
 * @param {'primary'|'secondary'|'outline'|'ghost'} variant
 * @param {'sm'|'md'|'lg'} size
 */
export default function Button({
  children,
  variant = 'primary',
  size = 'md',
  onClick,
  type = 'button',
  disabled = false,
  className = '',
}) {
  const classNames = `
    ${styles.btn} 
    ${styles[variant] || styles.primary} 
    ${styles[size] || styles.md} 
    ${className}
  `.trim().replace(/\s+/g, ' ');

  return (
    <button
      type={type}
      disabled={disabled}
      onClick={onClick}
      className={classNames}
    >
      {children}
    </button>
  );
}