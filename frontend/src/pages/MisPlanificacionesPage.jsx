import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import PlanificacionCard from '../components/planificacion/PlanificacionCard';

export default function MisPlanificacionesPage() {
  const { token, isAuthenticated, loading } = useAuth();
  const navigate = useNavigate();
  const [planificaciones, setPlanificaciones] = useState([]);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      navigate('/login');
    }
  }, [loading, isAuthenticated, navigate]);

  useEffect(() => {
    if (loading) return;
    if (!token) {
      setCargando(false);
      return;
    }

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
        console.error("Error cargando planificaciones:", err);
        setCargando(false);
      });
  }, [token, loading]);

  const handleEliminar = async (id) => {
    if (!confirm('¿Seguro que quieres eliminar esta planificación?')) return;

    try {
      const response = await fetch(`/api/planificaciones/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });

      if (response.ok) {
        setPlanificaciones(prevList => prevList.filter(p => p.id !== id));
      } else {
        const errorData = await response.json().catch(() => ({}));
        alert(errorData.message || 'El servidor no ha podido procesar el borrado de la ruta.');
      }
    } catch (err) {
      console.error("Error al eliminar la planificación:", err);
      alert('Error de conexión: No se ha podido comunicar el borrado a RutaRaíz.');
    }
  };

  if (loading || cargando) {
    return (
      <Container>
        <div className="text-center py-5 text-muted small">Consultando tu mochila de rutas...</div>
      </Container>
    );
  }

  return (
    <Container>
      <div className="d-flex justify-content-between align-items-center my-4 flex-wrap gap-3">
        <h1 className="h2 m-0" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
          Mis planificaciones
        </h1>
        <button
          className="btn text-white px-4 py-2"
          onClick={() => navigate('/planificador')}
          style={{ background: 'var(--verde-bosque)', fontWeight: '600', borderRadius: 'var(--radius-md)', border: 'none' }}
        >
          + Nueva planificación
        </button>
      </div>

      {/* Coloca esto justo encima del bucle .map de tus tarjetas */}
      {planificaciones && planificaciones.length > 0 && (
        <div className="row g-3 mb-4">
          {/* Card 1: Rutas Totales */}
          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center bg-light text-success" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px' }}>
                <i className="fa-solid fa-map-location-dot"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">MIS ITINERARIOS</h4>
                <span className="h4 mb-0 fw-bold text-dark">{planificaciones.length}</span>
              </div>
            </div>
          </div>

          {/* Card 2: Kilómetros Totales Planificados */}
          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center bg-light text-primary" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px' }}>
                <i className="fa-solid fa-route"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">KM TOTALES</h4>
                <span className="h4 mb-0 fw-bold text-dark">
                  {Math.round(planificaciones.reduce((acc, p) => acc + (p.km_dia * p.dias_totales), 0))} km
                </span>
              </div>
            </div>
          </div>

          {/* Card 3: Cuántas ha hecho públicas */}
          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px', backgroundColor: 'rgba(74, 114, 85, 0.1)', color: 'var(--verde-bosque)' }}>
                <i className="fa-solid fa-eye"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">COMPARTIDAS</h4>
                <span className="h4 mb-0 fw-bold text-dark">
                  {planificaciones.filter(p => p.is_public).length}
                </span>
              </div>
            </div>
          </div>
        </div>
      )}

      {planificaciones.length === 0 ? (
        <div className="text-center py-5 border rounded bg-white shadow-sm my-4" style={{ borderRadius: 'var(--radius-lg)' }}>
          <p className="text-muted mb-4">Todavía no tienes ninguna planificación guardada.</p>
          <button
            className="btn text-white px-4 py-2"
            onClick={() => navigate('/planificador')}
            style={{ background: 'var(--verde-bosque)', fontWeight: '600', borderRadius: 'var(--radius-md)', border: 'none' }}
          >
            Crear mi primera ruta
          </button>
        </div>
      ) : (
        <div className="mb-5 d-flex flex-column gap-3">
          {planificaciones.map(p => (
            <PlanificacionCard
              key={p.id}
              p={p}
              onVer={(id) => navigate(`/mis-planificaciones/${id}`)}
              onEliminar={handleEliminar}
            />
          ))}
        </div>
      )}
    </Container>
  );
}