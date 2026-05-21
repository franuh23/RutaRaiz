import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import AlojamientoFicha from '../components/alojamientos/AlojamientoFicha';
import AlojamientoComentarios from '../components/alojamientos/AlojamientoComentarios';

export default function AlojamientoDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { token, user } = useAuth();
  const [alojamiento, setAlojamiento] = useState(null);
  const [loading, setLoading] = useState(true);

  const cargarAlojamiento = () => {
    fetch(`/api/alojamientos/${id}`)
      .then((res) => res.json())
      .then((resData) => {
        setAlojamiento(resData.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error(err);
        setLoading(false);
      });
  };

  useEffect(() => {
    cargarAlojamiento();
  }, [id]);

  const handleEnviarComentario = async (texto) => {
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
        cargarAlojamiento(); // Recargar datos para pintar el nuevo comentario con su usuario
      }
    } catch (err) {
      console.error('Error al publicar comentario:', err);
    }
  };

  if (loading) return <Container><div className="text-center py-5 text-muted">Cargando detalles...</div></Container>;
  if (!alojamiento) return <Container><div className="alert alert-warning my-4">Alojamiento no encontrado.</div></Container>;

  return (
    <Container>
      <div className="py-4">
        {/* Ficha técnica con los datos limpios */}
        <AlojamientoFicha alojamiento={alojamiento} />

        {/* Módulo modular de Comentarios */}
        <AlojamientoComentarios 
          comentarios={alojamiento.comentarios} 
          onEnviarComentario={handleEnviarComentario} 
        />

        {/* Acciones inferiores de navegación */}
        <div className="d-flex gap-3 fs-5 mt-3 ps-2">
          {user?.rol === 'admin' && (
            <button 
              className="p-0 bg-transparent text-primary text-decoration-underline border-0"
              onClick={() => navigate('/admin')}
            >
              Editar
            </button>
          )}
          <button 
            className="p-0 bg-transparent text-primary text-decoration-underline border-0"
            onClick={() => navigate(-1)}
          >
            Volver
          </button>
        </div>
      </div>
    </Container>
  );
}