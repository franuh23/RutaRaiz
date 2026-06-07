import React, { useState } from 'react';
import styles from './LocalizacionCard.module.css';
// Tarjeta de hito geográfico.

export default function LocalizacionCard({ localizacion }) {
  const [alojamientosOpen, setAlojamientosOpen] = useState(false);
  const { nombre = 'Localización', distancia_desde_inicio = 0, descripcion, alojamientos = [] } = localizacion || {};

  return (
    <div className={styles.card}>
      <div 
        className={styles.header} 
        onClick={() => setAlojamientosOpen(!alojamientosOpen)}
      >
        <span className={styles.nombre}>{nombre}</span>
        <span className={styles.distancia}>{distancia_desde_inicio} km</span>
        <span className={styles.icono}>{alojamientosOpen ? '▲' : '▼'}</span>
      </div>
      
      {descripcion && (
        <p className={styles.descripcion}>{descripcion}</p>
      )}
      
      {alojamientosOpen && (
        <div className={styles.alojamientos}>
          <h4>Alojamientos</h4>
          {alojamientos.length > 0 ? (
            alojamientos.map(aloj => (
              <AlojamientoCard key={aloj.id} alojamiento={aloj} />
            ))
          ) : (
            <p className="text-muted small m-0">Sin alojamientos registrados</p>
          )}
        </div>
      )}
    </div>
  );
}

function AlojamientoCard({ alojamiento }) {
  const [detailsOpen, setDetailsOpen] = useState(false);
  const { nombre = 'Alojamiento', tipo = 'Albergue', direccion, telefono, email, enlace } = alojamiento || {};

  return (
    <div className={styles.alojamientoCard}>
      <div 
        className={styles.alojamientoHeader}
        onClick={() => setDetailsOpen(!detailsOpen)}
      >
        <span className={styles.alojamientoNombre}>{nombre}</span>
        <span className={styles.alojamientoTipo}>[{tipo}]</span>
        <span className={styles.icono}>{detailsOpen ? '▲' : '▼'}</span>
      </div>
      
      {detailsOpen && (
        <div className={styles.detalles}>
          {direccion && <p>📍 {direccion}</p>}
          {telefono && <p>📞 {telefono}</p>}
          {email && <p>✉️ {email}</p>}
          {enlace && (
            <p>🔗 <a href={enlace} target="_blank" rel="noopener noreferrer">Sitio web</a></p>
          )}
        </div>
      )}
    </div>
  );
}