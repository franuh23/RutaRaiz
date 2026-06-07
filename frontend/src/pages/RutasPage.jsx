import React, { useEffect, useState } from 'react';
import Container from '../components/layout/Container';
import RouteCardSimple from '../components/ui/RouteCardSimple';
import { apiFetch } from '../services/api';
// Catálogo completo de itinerarios.

export default function RutasPage() {
  const [rutas, setRutas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    apiFetch('/api/rutas')
      .then(res => res.json())
      .then(data => {
        setRutas(data.data || []);
        setLoading(false)
      })
      .catch(err => {
        console.error("Error al cargar las rutas:", err);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return (
      <Container>
        <div className="text-center py-5 text-muted small">Cargando catálogo de rutas...</div>
      </Container>
    );
  }

  return (
    <Container className="py-4">
      <div className="mb-4 text-start border-bottom pb-2">
        <h1 className="fw-bold m-0" style={{ fontFamily: 'var(--font-display)', color: 'var(--verde-bosque)' }}>
          Explorar Rutas
        </h1>
        <p className="text-muted small m-0 mt-1">
          Descubre todos los caminos históricos disponibles y selecciona tu próximo desafío.
        </p>
      </div>

      <div className="d-flex flex-column gap-3">
        {rutas.length > 0 ? (
          rutas.map(ruta => (
            <RouteCardSimple key={ruta.id} ruta={ruta} />
          ))
        ) : (
          <div className="text-center py-5 bg-light rounded text-muted small">
            No se han encontrado rutas en la red de RutaRaíz.
          </div>
        )}
      </div>
    </Container>
  );
}