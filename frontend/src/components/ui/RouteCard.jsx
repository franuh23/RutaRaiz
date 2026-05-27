import React from 'react';
import { useNavigate } from 'react-router-dom';
import Badge from '../ui/Badge';
import styles from './RouteCard.module.css';

// Corregidos los keys para que coincidan con el enum sin tildes del backend
const DIFFICULTY_VARIANT = {
  facil:   'difficulty-easy',
  media:   'difficulty-medium',
  dificil: 'difficulty-hard',
};

export default function RouteCard({ ruta }) {
  const navigate = useNavigate();

  // Desestructuramos usando estrictamente las columnas reales de tu base de datos
  const { 
    id,
    nombre = 'Ruta', 
    inicio,
    fin,
    kilometros = 0, 
    dificultad = 'media', 
    imagen, // Imagen local o URL de Neon
    descripcion 
  } = ruta || {};

  // Normalizamos quitando tildes por si acaso para asegurar el color del Badge
  const difNormalizada = dificultad
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");

  // Fallback de imagen de naturaleza si la ruta no tiene foto asignada en la BD
  const fotoPortada = imagen || "https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=500&q=80";

  return (
    <article 
      className={styles.card} 
      onClick={() => navigate(`/rutas/${id}`)}
      style={{ cursor: 'pointer' }} // Hace que toda la tarjeta sea clicable hacia sus etapas
    >
      {/* 🖼️ Contenedor de Imagen Real de la BD */}
      <div className={styles.imgWrapper}>
        <img 
          src={fotoPortada} 
          alt={nombre} 
          className="w-100 h-100 object-fit-cover"
          style={{ transition: 'transform 0.3s ease' }}
        />
        {/* Tramo Oficial sobreimpreso */}
        {(inicio && fin) && (
          <span className={`position-absolute bottom-2 start-2 badge bg-dark bg-opacity-70 text-white small`}>
            🏁 {inicio} ➔ {fin}
          </span>
        )}
      </div>

      {/* 📝 Contenido Informativo */}
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