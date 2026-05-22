import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import ResumenPlanificacion from '../components/planificacion/ResumenPlanificacion';
import EtapaCard from '../components/planificacion/EtapaCard';

export default function PlanificacionDetallePage() {
  const { id } = useParams();
  const { token, isAuthenticated, loading } = useAuth();
  const navigate = useNavigate();
  const [planificacion, setPlanificacion] = useState(null);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    if (!loading && !isAuthenticated) {
      navigate('/login');
    }
  }, [loading, isAuthenticated, navigate]);

  useEffect(() => {
    if (loading) return;
    if (!token) return;

    fetch(`/api/planificaciones/${id}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
      .then(res => {
        if (!res.ok) throw new Error('Planificación no localizada');
        return res.json();
      })
      .then(data => {
        setPlanificacion(data.data);
        setCargando(false);
      })
      .catch(err => {
        console.error(err);
        setCargando(false);
        navigate('/mis-planificaciones');
      });
  }, [token, id, loading, navigate]);

  if (loading || cargando) {
    return (
      <Container>
        <div className="text-center py-5 text-muted small">Abriendo cuaderno de ruta...</div>
      </Container>
    );
  }

  if (!planificacion) return null;

  return (
    <Container className="pb-5">
      <div className="d-flex align-items-center gap-3 my-4 flex-wrap">
        <button 
          className="btn btn-outline-success px-3 btn-sm" 
          onClick={() => navigate('/mis-planificaciones')}
          style={{ fontWeight: '600', borderRadius: 'var(--radius-md)' }}
        >
          ← Volver
        </button>
        <h1 className="h2 m-0" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
          {planificacion.ruta_nombre}
        </h1>
      </div>

      <ResumenPlanificacion planificacion={planificacion} />

      <div className="mt-4">
        <h2 className="h5 mb-3 ps-2" style={{ borderLeft: '4px solid var(--oro)', color: 'var(--verde-bosque)', fontWeight: '700' }}>
          📋 Etapas del recorrido personalizado
        </h2>
        {planificacion.etapas?.length > 0 ? (
          <div className="d-flex flex-column gap-2">
            {planificacion.etapas.map((etapa) => (
              <EtapaCard key={etapa.id} etapa={etapa} />
            ))}
          </div>
        ) : (
          <p className="text-muted text-center py-4 bg-light rounded small">No hay etapas configuradas en esta marcha.</p>
        )}
      </div>
    </Container>
  );
}