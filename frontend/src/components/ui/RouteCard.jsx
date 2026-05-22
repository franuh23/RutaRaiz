import React from 'react';
import Badge from '../ui/Badge';
import styles from './RouteCard.module.css';

const DIFFICULTY_VARIANT = {
  fácil:   'difficulty-easy',
  media:   'difficulty-medium',
  difícil: 'difficulty-hard',
};

export default function RouteCard({ ruta }) {
  const { 
    nombre = 'Ruta', 
    zona = 'General', 
    kilometros = 0, 
    dificultad = 'media', 
    etiqueta, 
    emoji = '👣', 
    gradient = 'linear-gradient(135deg, var(--verde-hoja), var(--verde-bosque))', 
    descripcion 
  } = ruta || {};

  // Normalizamos el texto de la dificultad para evitar fallos de matching
  const difNormalizada = dificultad.toLowerCase();

  return (
    <article className={styles.card}>
      <div className={styles.imgWrapper} style={{ background: gradient }}>
        <span className={styles.emoji} role="img" aria-label={nombre}>{emoji}</span>
        {etiqueta && <Badge variant="gold-solid" className={styles.etiqueta}>{etiqueta}</Badge>}
      </div>

      <div className={styles.body}>
        <div className={styles.meta}>
          <span>📍 {zona}</span>
          <span>👣 {Number(kilometros).toLocaleString('es-ES')} km</span>
        </div>
        <h3 className={styles.nombre}>{nombre}</h3>
        <p className={styles.desc}>{descripcion || 'Explora este maravilloso itinerario.'}</p>
        <Badge variant={DIFFICULTY_VARIANT[difNormalizada] || 'default'}>
          {difNormalizada.charAt(0).toUpperCase() + difNormalizada.slice(1)}
        </Badge>
      </div>
    </article>
  );
}