import { useNavigate } from 'react-router-dom';
import Badge from './Badge';
import styles from './RouteCardSimple.module.css';

const DIFFICULTY_VARIANT = {
  baja: 'difficulty-easy',
  media: 'difficulty-medium',
  alta: 'difficulty-hard',
};

export default function RouteCardSimple({ ruta }) {
  const navigate = useNavigate();

  const handleClick = () => {
    navigate(`/rutas/${ruta.id}`);
  };

  return (
    <article className={styles.card} onClick={handleClick} style={{ cursor: 'pointer' }}>
      {ruta.imagen && (
        <div className={styles.imageWrapper}>
          <img src={ruta.imagen} alt={ruta.nombre} className={styles.image} />
        </div>
      )}
      <div className={styles.body}>
        <h3 className={styles.nombre}>{ruta.nombre}</h3>
        <p className={styles.desc}>{ruta.descripcion || 'Sin descripción'}</p>
        <div className={styles.meta}>
          <span>📍 {ruta.inicio} → {ruta.fin}</span>
          <span>👣 {ruta.kilometros} km</span>
        </div>
        <Badge variant={DIFFICULTY_VARIANT[ruta.dificultad]}>
          {ruta.dificultad?.charAt(0).toUpperCase() + ruta.dificultad?.slice(1)}
        </Badge>
      </div>
    </article>
  );
}