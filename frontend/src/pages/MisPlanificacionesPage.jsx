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
  const [activandoId, setActivandoId] = useState(null);

  // Buscamos si existe alguna planificación activa en curso
  const rutaEnCursoActual = planificaciones.find(p => p.en_curso);

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

  // Disparador hacia Neon para activar el seguimiento de la ruta
  const handleEmpezarRuta = async (id) => {
    setActivandoId(id);
    try {
      const response = await fetch(`/api/planificaciones/${id}/empezar`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });

      const resData = await response.json();

      if (response.ok) {
        // Recorremos el estado y actualizamos en caliente apagando las demás y encendiendo la elegida
        setPlanificaciones(prev => prev.map(p => {
          if (p.id === id) return { ...p, en_curso: true };
          return { ...p, en_curso: false };
        }));
        navigate('/seguimiento'); // Saltamos directo a la nueva pantalla operativa
      } else {
        alert(resData.message || 'No se pudo iniciar el seguimiento.');
      }
    } catch (err) {
      console.error("Error al activar el seguimiento:", err);
      alert('Error de conexión con el servidor.');
    } finally {
      setActivandoId(null);
    }
  };

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
      {/* 🧭 BANNER DE ACCESO DIRECTO: Si hay una ruta en curso, asoma arriba del todo */}
      {rutaEnCursoActual && (
        <div className="alert alert-info border-0 p-3 my-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style={{ backgroundColor: '#e3f2fd', borderRadius: 'var(--radius-md)' }}>
          <div className="d-flex align-items-center gap-2">
            <i className="fa-solid fa-compass text-primary fs-5 animate__animated animate__pulse animate__infinite"></i>
            <span className="text-dark fw-medium small">
              Tienes el <strong>{rutaEnCursoActual.ruta_nombre}</strong> en curso actualmente.
            </span>
          </div>
          <button 
            className="btn btn-sm btn-primary px-3 py-1 fw-bold" 
            style={{ borderRadius: 'var(--radius-sm)', fontSize: '11px' }}
            onClick={() => navigate('/seguimiento')}
          >
            VER SEGUIMIENTO ACTIVE ➔
          </button>
        </div>
      )}

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

      {planificaciones && planificaciones.length > 0 && (
        <div className="row g-3 mb-4">
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
          <button className="btn text-white px-4 py-2" onClick={() => navigate('/planificador')} style={{ background: 'var(--verde-bosque)', fontWeight: '600', borderRadius: 'var(--radius-md)', border: 'none' }}>
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
              onEmpezar={handleEmpezarRuta}
              activandoId={activandoId}
            />
          ))}
        </div>
      )}
    </Container>
  );
}