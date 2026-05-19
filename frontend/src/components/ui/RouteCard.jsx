import Badge from '../ui/Badge';
import styles from './RouteCard.module.css';

const DIFFICULTY_VARIANT = {
  fácil:   'difficulty-easy',
  media:   'difficulty-medium',
  difícil: 'difficulty-hard',
};

/**
 * RouteCard
 * Muestra info de una ruta: imagen/gradiente, nombre, zona, km, dificultad.
 */
export default function RouteCard({ ruta }) {
  const { nombre, zona, kilometros, dificultad, etiqueta, emoji, gradient, descripcion } = ruta;

  return (
    <article className={styles.card}>
      {/* Imagen / gradiente */}
      <div className={styles.imgWrapper} style={{ background: gradient }}>
        <span className={styles.emoji} role="img" aria-label={nombre}>{emoji}</span>
        <Badge variant="gold-solid" className={styles.etiqueta}>{etiqueta}</Badge>
      </div>

      {/* Info */}
      <div className={styles.body}>
        <div className={styles.meta}>
          <span>📍 {zona}</span>
          <span>👣 {kilometros.toLocaleString('es-ES')} km</span>
        </div>
        <h3 className={styles.nombre}>{nombre}</h3>
        <p className={styles.desc}>{descripcion}</p>
        <Badge variant={DIFFICULTY_VARIANT[dificultad]}>
          {dificultad.charAt(0).toUpperCase() + dificultad.slice(1)}
        </Badge>
      </div>
    </article>
  );
}
