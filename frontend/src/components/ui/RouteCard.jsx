import React from 'react';
import { useNavigate } from 'react-router-dom';
import Badge from '../ui/Badge';
import styles from './RouteCard.module.css';
// Tarjeta informativa de ruta.

const DIFFICULTY_VARIANT = {
  baja:   'difficulty-easy',
  media:   'difficulty-medium',
  alta: 'difficulty-hard',
};

export default function RouteCard({ ruta }) {
  const navigate = useNavigate();

  const { 
    id,
    nombre = 'Ruta', 
    inicio,
    fin,
    kilometros = 0, 
    dificultad = 'media', 
    imagen,
    descripcion 
  } = ruta || {};

  const difNormalizada = dificultad
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");

  const fotoPortada = imagen || "https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=500&q=80";

  return (
    <article 
      className={styles.card} 
      onClick={() => navigate(`/rutas/${id}`)}
      style={{ cursor: 'pointer' }}
    >
      <div className={styles.imgWrapper}>
        <img 
          src={fotoPortada} 
          alt={nombre} 
          className="w-100 h-100 object-fit-cover"
          style={{ transition: 'transform 0.3s ease' }}
        />
        {(inicio && fin) && (
          <span className={`position-absolute bottom-2 start-2 badge bg-dark bg-opacity-70 text-white small`}>
            🏁 {inicio} ➔ {fin}
          </span>
        )}
      </div>

      <div className={styles.body}>
        <div className={styles.meta}>
          <span className="fw-bold" style={{ color: 'var(--verde-medio)' }}>🗺️Ruta popular</span>
          <span className="fw-bold font-monospace text-dark">👣 {Number(kilometros).toLocaleString('es-ES')} km</span>
        </div>
        
        
        <h3 className={styles.nombre}>{nombre}</h3>
        
        <p className={styles.desc}>
          {descripcion || 'Explora este maravilloso itinerario histórico en RutaRaíz.'}
        </p>
        
        <div className="d-flex justify-content-between align-items-center mt-auto">
          <Badge variant={DIFFICULTY_VARIANT[difNormalizada] || 'default'}>
            {dificultad.charAt(0).toUpperCase() + dificultad.slice(1).toLowerCase()}
          </Badge>
          
          <span className="text-success fw-bold small">Ver etapas ➔</span>
        </div>
      </div>
    </article>
  );
}