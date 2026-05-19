import { useState } from 'react';
import styles from './LocalizacionCard.module.css';

export default function LocalizacionCard({ localizacion }) {
  const [alojamientosOpen, setAlojamientosOpen] = useState(false);

  return (
    <div className={styles.card}>
      <div 
        className={styles.header} 
        onClick={() => setAlojamientosOpen(!alojamientosOpen)}
      >
        <span className={styles.nombre}>{localizacion.nombre}</span>
        <span className={styles.distancia}>{localizacion.distancia_desde_inicio} km</span>
        <span className={styles.icono}>{alojamientosOpen ? '▲' : '▼'}</span>
      </div>
      
      {localizacion.descripcion && (
        <p className={styles.descripcion}>{localizacion.descripcion}</p>
      )}
      
      {alojamientosOpen && (
        <div className={styles.alojamientos}>
          <h4>Alojamientos</h4>
          {localizacion.alojamientos?.length > 0 ? (
            localizacion.alojamientos.map(aloj => (
              <AlojamientoCard key={aloj.id} alojamiento={aloj} />
            ))
          ) : (
            <p>Sin alojamientos registrados</p>
          )}
        </div>
      )}
    </div>
  );
}

function AlojamientoCard({ alojamiento }) {
  const [detailsOpen, setDetailsOpen] = useState(false);

  return (
    <div className={styles.alojamientoCard}>
      <div 
        className={styles.alojamientoHeader}
        onClick={() => setDetailsOpen(!detailsOpen)}
      >
        <span className={styles.alojamientoNombre}>{alojamiento.nombre}</span>
        <span className={styles.alojamientoTipo}>[{alojamiento.tipo}]</span>
        <span className={styles.icono}>{detailsOpen ? '▲' : '▼'}</span>
      </div>
      
      {detailsOpen && (
        <div className={styles.detalles}>
          {alojamiento.direccion && <p>📍 {alojamiento.direccion}</p>}
          {alojamiento.telefono && <p>📞 {alojamiento.telefono}</p>}
          {alojamiento.email && <p>✉️ {alojamiento.email}</p>}
          {alojamiento.enlace && (
            <p>🔗 <a href={alojamiento.enlace} target="_blank" rel="noopener noreferrer">Sitio web</a></p>
          )}
        </div>
      )}
    </div>
  );
}