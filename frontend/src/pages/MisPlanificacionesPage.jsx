import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import styles from './MisPlanificacionesPage.module.css';

export default function MisPlanificacionesPage() {
  const { token, isAuthenticated, loading } = useAuth();
  const navigate = useNavigate();
  const [planificaciones, setPlanificaciones] = useState([]);
  const [cargando, setCargando] = useState(true);

  // Si no está logueado, redirigir al login
  useEffect(() => {
    if (!loading && !isAuthenticated) {
      navigate('/login');
    }
  }, [loading, isAuthenticated, navigate]);

  // Cargar planificaciones del usuario
  useEffect(() => {
    if (!token) return;

    fetch('/api/planificaciones', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
      .then(res => res.json())
      .then(data => {
        setPlanificaciones(data.data || []);
        setCargando(false);
      })
      .catch(err => {
        console.error(err);
        setCargando(false);
      });
  }, [token]);

  const handleEliminar = async (id) => {
    if (!confirm('¿Seguro que quieres eliminar esta planificación?')) return;

    await fetch(`/api/planificaciones/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    setPlanificaciones(planificaciones.filter(p => p.id !== id));
  };

  if (loading || cargando) return <Container><p>Cargando...</p></Container>;

  return (
    <Container>
      <div className={styles.header}>
        <h1 className={styles.titulo}>Mis planificaciones</h1>
        <button className={styles.btnNueva} onClick={() => navigate('/planificador')}>
          + Nueva planificación
        </button>
      </div>

      {planificaciones.length === 0 ? (
        <div className={styles.vacio}>
          <p>Todavía no tienes ninguna planificación guardada.</p>
          <button className={styles.btnNueva} onClick={() => navigate('/planificador')}>
            Crear mi primera ruta
          </button>
        </div>
      ) : (
        <div className={styles.lista}>
          {planificaciones.map(p => (
            <div key={p.id} className={styles.card}>
              <div className={styles.cardInfo}>
                <h3 className={styles.rutaNombre}>{p.ruta_nombre}</h3>
                <div className={styles.meta}>
                  <span>📅 Inicio: {p.fecha_inicio}</span>
                  <span>👣 {p.km_dia} km/día</span>
                  <span>🗓️ {p.dias_totales} días</span>
                </div>
                <div className={styles.meta}>
                  <span>📍 {p.localizacion_inicio_nombre} → {p.localizacion_fin_nombre || 'Final de ruta'}</span>
                </div>
              </div>
              <div className={styles.cardActions}>
                <button
                  className={styles.btnVer}
                  onClick={() => navigate(`/mis-planificaciones/${p.id}`)}
                >
                  Ver etapas
                </button>
                <button
                  className={styles.btnEliminar}
                  onClick={() => handleEliminar(p.id)}
                >
                  Eliminar
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </Container>
  );
}