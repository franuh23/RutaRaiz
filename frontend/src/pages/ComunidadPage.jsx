import React, { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import ComunidadCard from '../components/comunidad/ComunidadCard';
import { apiFetch } from '../services/api';
// Vista del muro de la comunidad, delega en ComunidadCard.

export default function ComunidadView() {
  const { token, usuario } = useAuth();
  const [planificaciones, setPlanificaciones] = useState([]);
  const [cargando, setCargando] = useState(true);
  const [clonandoId, setClonandoId] = useState(null);

  useEffect(() => {
    fetchPublicas();
  }, [token]);

  const fetchPublicas = async () => {
    try {
      const res = await apiFetch('/api/comunidad/planificaciones', {
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await res.json();
      if (res.ok) {
        setPlanificaciones(data.data || []);
      }
    } catch (err) {
      console.error("Error al cargar la comunidad:", err);
    } finally {
      setCargando(false);
    }
  };

  const handleLike = async (id) => {
    try {
      const res = await apiFetch(`/api/comunidad/planificaciones/${id}/like`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await res.json();
      
      if (res.ok) {
        setPlanificaciones(prev => prev.map(p => {
          if (p.id === id) {
            return {
              ...p,
              ha_dado_like: data.ha_dado_like,
              likes_count: data.likes_count
            };
          }
          return p;
        }));
      }
    } catch (err) {
      console.error("Error al procesar el like:", err);
    }
  };

  const handleClonar = async (id) => {
    setClonandoId(id);
    try {
      const res = await apiFetch(`/api/comunidad/planificaciones/${id}/clonar`, {
        method: 'POST',
        headers: { 'Authorization': `Bearer ${token}` }
      });
      const data = await res.json();

      if (res.ok) {
        alert(data.message || '¡Ruta copiada con éxito! Ya puedes verla en "Mis Planificaciones".');
      } else {
        alert(data.error || 'No se pudo clonar la ruta.');
      }
    } catch (err) {
      console.error("Error al clonar:", err);
      alert('Hubo un error del servidor al intentar copiar la ruta.');
    } finally {
      setClonandoId(null);
    }
  };

  return (
    <div className="container py-4" style={{ minHeight: '80vh' }}>
      <div className="mb-4">
        <h1 style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '800' }}>
          🎒 Albergue de la Comunidad
        </h1>
        <p className="text-muted">
          Descubre los itinerarios diseñados por otros peregrinos. Inspírate, dales tu apoyo o añádelos directamente a tu mochila para tu próximo viaje.
        </p>
      </div>

      {cargando ? (
        <div className="d-flex justify-content-center py-5">
          <div className="spinner-border text-success" role="status">
            <span className="visually-hidden">Cargando comunidad...</span>
          </div>
        </div>
      ) : planificaciones.length === 0 ? (
        <div className="text-center py-5 bg-white shadow-sm rounded-3">
          <p className="text-muted mb-0">Nadie ha publicado ninguna planificación todavía. ¡Sé el primero en romper el hielo!</p>
        </div>
      ) : (
        <div className="row">
          <div className="col-12 col-lg-9 mx-auto">
            {planificaciones.map(p => (
              <ComunidadCard
                key={p.id}
                p={p}
                onLike={handleLike}
                onClonar={handleClonar}
                clonandoId={clonandoId}
                usuarioLogueadoId={usuario?.id}
              />
            ))}
          </div>
        </div>
      )}
    </div>
  );
}