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
    try {
      await fetch(`/api/planificaciones/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });
      setPlanificaciones(planificaciones.filter(p => p.id !== id));
    } catch (err) {
      console.error(err);
    }
  };

  if (loading || cargando) {
    return (
      <Container>
        <div className="text-center py-5 text-muted">Cargando...</div>
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
          style={{ background: 'var(--verde-bosque)', fontWeight: '600', borderRadius: 'var(--radius-md)' }}
        >
          + Nueva planificación
        </button>
      </div>

      {planificaciones.length === 0 ? (
        <div className="text-center py-5 border rounded bg-white shadow-sm my-4" style={{ borderRadius: 'var(--radius-lg)' }}>
          <p className="text-muted mb-4">Todavía no tienes ninguna planificación guardada.</p>
          <button 
            className="btn text-white px-4 py-2" 
            onClick={() => navigate('/planificador')}
            style={{ background: 'var(--verde-bosque)', fontWeight: '600', borderRadius: 'var(--radius-md)' }}
          >
            Crear mi primera ruta
          </button>
        </div>
      ) : (
        <div className="mb-5">
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