import React, { useEffect, useState, useCallback } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import AlojamientoFicha from '../components/alojamientos/AlojamientoFicha';
import AlojamientoComentarios from '../components/alojamientos/AlojamientoComentarios';
import { apiFetch } from '../services/api';

export default function AlojamientoDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { token, user } = useAuth();
  const [alofamiento, setAlojamiento] = useState(null);
  const [loading, setLoading] = useState(true);

  const cargarAlojamiento = useCallback(() => {
    apiFetch(`/api/alojamientos/${id}`)
      .then((res) => res.json())
      .then((resData) => {
        setAlojamiento(resData.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error al refrescar albergue:", err);
        setLoading(false);
      });
  }, [id]);

  useEffect(() => {
    cargarAlojamiento();
  }, [cargarAlojamiento]);

  const handleEnviarComentario = async (texto) => {
    if (!texto.trim()) return;
    try {
      const res = await apiFetch('/api/comentarios-alojamiento', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          alojamiento_id: id,
          texto: texto
        })
      });

      const dataJson = await res.json();

      if (res.ok) {
        setAlojamiento(prev => ({
          ...prev,
          comentarios: [...(prev.comentarios || []), dataJson.data]
        }));
      } else {
        alert(dataJson.message || 'Error al publicar el comentario');
      }
    } catch (err) {
      console.error('Error al publicar comentario:', err);
    }
  };

  const handleBorrarComentario = async (comentarioId) => {
    try {
      const res = await apiFetch(`/api/comentarios-alojamiento/${comentarioId}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      });

      const dataJson = await res.json();

      if (res.ok) {
        // 🧼 Quitamos el comentario del estado en caliente para que desaparezca visualmente
        setAlojamiento(prev => ({
          ...prev,
          comentarios: (prev.comentarios || []).filter(c => c.id !== comentarioId)
        }));
      } else {
        alert(dataJson.message || 'No se pudo eliminar el comentario');
      }
    } catch (err) {
      console.error('Error al borrar comentario:', err);
    }
  };

  if (loading) return <Container><div className="text-center py-5 text-muted small">Abriendo libro de registro...</div></Container>;
  if (!alofamiento) return <Container><div className="alert alert-warning my-4" style={{ borderRadius: 'var(--radius-md)' }}>Alojamiento no encontrado.</div></Container>;

  return (
    <Container className="mb-5">
      <div className="py-2">
        <AlojamientoFicha alojamiento={alofamiento} />

        <AlojamientoComentarios
          comentarios={alofamiento.comentarios || []}
          onEnviarComentario={handleEnviarComentario}
          onBorrarComentario={handleBorrarComentario}
          currentUser={user}
        />

        <div className="d-flex gap-3 small mt-4 justify-content-end border-top pt-2">
          {user?.rol === 'admin' && (
            <button
              className="btn btn-sm btn-link text-primary text-decoration-none fw-semibold"
              onClick={() => navigate('/admin')}
            >
              🛠️ Panel de Control
            </button>
          )}
          <button
            className="btn btn-sm btn-outline-secondary px-3"
            onClick={() => navigate(-1)}
            style={{ borderRadius: 'var(--radius-md)' }}
          >
            Volver atrás
          </button>
        </div>
      </div>
    </Container>
  );
}