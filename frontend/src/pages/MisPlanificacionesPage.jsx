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

  // 🧭 Buscamos si existe alguna planificación activa en curso (para el banner superior)
  const rutaEnCursoActual = planificaciones.find(p => p.en_curso);

  // 🚀 SEPARACIÓN DE RUTAS POR ESTADO REAL DE NEON
  const rutasPlanificadas = planificaciones.filter(p => p.activo);
  const rutasCompletadas = planificaciones.filter(p => !p.activo);

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
        setPlanificaciones(prev => prev.map(p => {
          if (p.id === id) return { ...p, en_curso: true };
          return { ...p, en_curso: false };
        }));
        navigate('/seguimiento');
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

      {/* 📊 TARJETAS ESTADÍSTICAS REPARADAS: Solo suman las rutas vivas/planificadas */}
      {planificaciones && planificaciones.length > 0 && (
        <div className="row g-3 mb-5">
          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center bg-light text-success" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px' }}>
                <i className="fa-solid fa-map-location-dot"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">PLANIFICADAS</h4>
                <span className="h4 mb-0 fw-bold text-dark">{rutasPlanificadas.length}</span>
              </div>
            </div>
          </div>

          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center bg-light text-primary" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px' }}>
                <i className="fa-solid fa-route"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">KM FUTUROS</h4>
                <span className="h4 mb-0 fw-bold text-dark">
                  {Math.round(rutasPlanificadas.reduce((acc, p) => acc + (p.km_dia * p.dias_totales), 0))} km
                </span>
              </div>
            </div>
          </div>

          <div className="col-12 col-sm-4">
            <div className="card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3" style={{ borderRadius: 'var(--radius-md)' }}>
              <div className="d-flex align-items-center justify-content-center bg-warning text-dark" style={{ width: '45px', height: '45px', borderRadius: '50%', fontSize: '20px', backgroundColor: '#fff8e1' }}>
                <i className="fa-solid fa-trophy"></i>
              </div>
              <div>
                <h4 className="text-muted small mb-0 fw-bold">COMPLETADAS</h4>
                <span className="h4 mb-0 fw-bold text-dark">
                  {rutasCompletadas.length}
                </span>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* 🧭 BANNER DE ACCESO DIRECTO */}
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
            VER SEGUIMIENTO ACTIVO ➔
          </button>
        </div>
      )}

      {/* 📂 SECCIÓN 1: ITINERARIOS PLANIFICADOS */}
      <h3 className="h5 fw-bold mb-3 text-muted text-uppercase" style={{ fontSize: '13px', letterSpacing: '0.05em' }}>
        🎒 Próximos Desafíos ({rutasPlanificadas.length})
      </h3>
      
      {rutasPlanificadas.length === 0 ? (
        <div className="text-center py-4 border rounded bg-white shadow-sm mb-5" style={{ borderRadius: 'var(--radius-lg)' }}>
          <p className="text-muted small mb-0">No tienes itinerarios pendientes en tu mochila.</p>
        </div>
      ) : (
        <div className="mb-5 d-flex flex-column gap-3">
          {rutasPlanificadas.map(p => (
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

      {/* 🏆 SECCIÓN 2: HISTORIAL DE ÉXITOS PEREGRINOS (Solo asoma si hay alguna completa) */}
      {rutasCompletadas.length > 0 && (
        <div className="mt-5 mb-5">
          <h3 className="h5 fw-bold mb-3 text-warning text-uppercase" style={{ fontSize: '13px', letterSpacing: '0.05em' }}>
            🏆 Historial de Caminos Completados ({rutasCompletadas.length})
          </h3>
          <div className="d-flex flex-column gap-3">
            {rutasCompletadas.map(p => (
              <div key={p.id} className="opacity-75">
                <PlanificacionCard
                  p={{ ...p, ruta_nombre: `🎉 ${p.ruta_nombre} (¡Completado!)` }}
                  onVer={(id) => navigate(`/mis-planificaciones/${id}`)}
                  onEliminar={handleEliminar}
                  onEmpezar={null} // Al pasar null, no saldrá el botón de empezar ruta otra vez
                />
              </div>
            ))}
          </div>
        </div>
      )}
    </Container>
  );
}