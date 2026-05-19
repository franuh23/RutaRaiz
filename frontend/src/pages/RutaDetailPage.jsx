import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import styles from './RutaDetailPage.module.css';
import LocalizacionCard from '../components/ui/LocalizacionCard';

export default function RutaDetailPage() {
  const { id } = useParams();
  const [ruta, setRuta] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`/api/rutas/${id}`)
      .then(res => res.json())
      .then(data => {
        setRuta(data.data);
        setLoading(false);
      })
      .catch(err => console.error(err));
  }, [id]);

  if (loading) return <div className={styles.container}><p>Cargando...</p></div>;
  if (!ruta) return <div className={styles.container}><p>Ruta no encontrada</p></div>;

  return (
    <div className={styles.container}>
      <h1 className={styles.titulo}>{ruta.nombre}</h1>

      {ruta.imagen && (
        <img src={ruta.imagen} alt={ruta.nombre} className={styles.imagen} />
      )}

      <div className={styles.info}>
        <div className={styles.infoGrid}>
          <span className={styles.infoItem}>🎒 Dificultad: {ruta.dificultad}</span>
          <span className={styles.infoItem}>📏 {ruta.kilometros} km</span>
          <span className={styles.infoItem}>📍 {ruta.inicio} → {ruta.fin}</span>
        </div>
        <p className={styles.descripcion}>{ruta.descripcion || 'Sin descripción'}</p>
      </div>

      <div className={styles.seccion}>
        <h3>📌 Localizaciones y alojamientos</h3>
        {ruta.localizaciones?.length > 0 ? (
          ruta.localizaciones.map(loc => (
            <LocalizacionCard key={loc.id} localizacion={loc} />
          ))
        ) : (
          <p>Sin localizaciones registradas</p>
        )}
      </div>
    </div>
  );
}