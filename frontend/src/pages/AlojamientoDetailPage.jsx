import React, { useEffect, useState, useCallback } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import AlojamientoFicha from '../components/alojamientos/AlojamientoFicha';
import AlojamientoComentarios from '../components/alojamientos/AlojamientoComentarios';

export default function AlojamientoDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { token, user } = useAuth();
  const [alofamiento, setAlojamiento] = useState(null);
  const [loading, setLoading] = useState(true);

  const cargarAlojamiento = useCallback(() => {
    fetch(`/api/alojamientos/${id}`)
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
      const res = await fetch('/api/comentarios', {
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
      if (res.ok) {
        cargarAlojamiento();
      }
    } catch (err) {
      console.error('Error al publicar comentario:', err);
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