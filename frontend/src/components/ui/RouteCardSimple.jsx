import React from 'react';
import { useNavigate } from 'react-router-dom';
import Badge from './Badge';
import styles from './RouteCardSimple.module.css';
// Variante simplificada de tarjeta de ruta.

const DIFFICULTY_VARIANT = {
  baja: 'difficulty-easy',
  fácil: 'difficulty-easy',
  media: 'difficulty-medium',
  alta: 'difficulty-hard',
  difícil: 'difficulty-hard',
};

export default function RouteCardSimple({ ruta }) {
  const navigate = useNavigate();
  const { id, imagen, nombre = 'Ruta', descripcion, inicio, fin, kilometros = 0, dificultad = 'media' } = ruta || {};

  const difNormalizada = dificultad.toLowerCase();

  return (
    <article className={styles.card} onClick={() => id && navigate(`/rutas/${id}`)} style={{ cursor: 'pointer' }}>
      {imagen && (
        <div className={styles.imageWrapper}>
          <img src={imagen} alt={nombre} className={styles.image} />
        </div>
      )}
      <div className={styles.body}>
        <h3 className={styles.nombre}>{nombre}</h3>
        <p className={styles.desc}>{descripcion || 'Sin descripción disponible.'}</p>
        <div className={styles.meta}>
          {inicio && <span>📍 {inicio} → {fin || 'Fin'}</span>}
          <span>👣 {kilometros} km</span>
        </div>
        <Badge variant={DIFFICULTY_VARIANT[difNormalizada] || 'default'}>
          {difNormalizada.charAt(0).toUpperCase() + difNormalizada.slice(1)}
        </Badge>
      </div>
    </article>
  );
}