import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import Container from '../components/layout/Container';
import LocalizacionCard from '../components/ui/LocalizacionCard';
import styles from './RutaDetailPage.module.css';

export default function RutaDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [ruta, setRuta] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`/api/rutas/${id}`)
      .then(res => res.json())
      .then(data => {
        setRuta(data.data);
        setLoading(false);
      })
      .catch(err => {
        console.error("Error al cargar el detalle de la ruta:", err);
        setLoading(false);
      });
  }, [id]);

  if (loading) {
    return (
      <Container>
        <div className="text-center py-5 text-muted small">Cargando detalles del itinerario...</div>
      </Container>
    );
  }

  if (!ruta) {
    return (
      <Container>
        <div className="text-center py-5">
          <p className="text-danger fw-semibold">Ruta no encontrada</p>
          <button className="btn btn-sm btn-outline-secondary mt-2" onClick={() => navigate('/rutas')}>
            Volver al catálogo
          </button>
        </div>
      </Container>
    );
  }

  return (
    <Container className="py-4">
      {/* Botón de retorno rápido */}
      <button 
        className="btn btn-sm btn-link text-decoration-none text-muted p-0 mb-3"
        onClick={() => navigate('/rutas')}
      >
        ← Volver a todas las rutas
      </button>

      {/* Título de la Ruta */}
      <h1 className="fw-bold mb-3" style={{ fontFamily: 'var(--font-display)', color: 'var(--verde-bosque)' }}>
        {ruta.nombre}
      </h1>

      {/* Imagen destacada utilizando un contenedor redondeado de Bootstrap */}
      {ruta.imagen && (
        <div className="mb-4 overflow-hidden rounded-4 shadow-sm">
          <img src={ruta.imagen} alt={ruta.nombre} className={`${styles.imagen} w-100`} style={{ objectFit: 'cover', maxHeight: '350px' }} />
        </div>
      )}

      {/* Ficha técnica de la ruta */}
      <div className="p-3 rounded-4 mb-4" style={{ backgroundColor: 'var(--crema-oscura)' }}>
        {/* Rejilla de Bootstrap flexible: 1 columna en móvil, 3 en pantallas grandes */}
        <div className="row g-2 text-center mb-3">
          <div className="col-12 col-md-4">
            <div className="bg-white p-2 rounded-3 shadow-sm small fw-semibold text-secondary">
              🎒 Dificultad: <span className="text-dark">{ruta.dificultad}</span>
            </div>
          </div>
          <div className="col-12 col-md-4">
            <div className="bg-white p-2 rounded-3 shadow-sm small fw-semibold text-secondary">
              📏 Distancia: <span className="text-dark">{ruta.kilometros} km</span>
            </div>
          </div>
          <div className="col-12 col-md-4">
            <div className="bg-white p-2 rounded-3 shadow-sm small fw-semibold text-secondary">
              📍 Trayecto: <span className="text-dark">{ruta.inicio} → {ruta.fin}</span>
            </div>
          </div>
        </div>
        
        <p className="m-0 text-secondary small lh-base px-1">
          {ruta.descripcion || 'Sin descripción disponible para este camino histórico.'}
        </p>
      </div>

      {/* Bloque desplegable de Localizaciones (Acordeones) */}
      <div className="mt-4">
        <h3 className="fw-bold mb-3" style={{ fontFamily: 'var(--font-display)', color: 'var(--verde-hoja)', fontSize: '1.3rem' }}>
          📌 Localizaciones y alojamientos del recorrido
        </h3>
        <div className="d-flex flex-column gap-1">
          {ruta.localizaciones?.length > 0 ? (
            ruta.localizaciones.map(loc => (
              <LocalizacionCard key={loc.id} localizacion={loc} />
            ))
          ) : (
            <div className="text-center py-4 bg-light rounded text-muted small">
              No hay paradas ni albergues registrados en este itinerario.
            </div>
          )}
        </div>
      </div>
    </Container>
  );
}