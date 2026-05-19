import styles from './Button.module.css';

/**
 * Button
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
  return (
    <button
      type={type}
      disabled={disabled}
      onClick={onClick}
      className={[
        styles.btn,
        styles[variant],
        styles[size],
        className,
      ].join(' ')}
    >
      {children}
    </button>
  );
}
