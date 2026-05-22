import React from 'react';
import { useNavigate } from 'react-router-dom';
import RouteCard from '../ui/RouteCard';

export default function RoutesPreviewSection({ rutas }) {
  const navigate = useNavigate();

  return (
    /* Pasamos el padding, el fondo crema en línea y las esquinas redondeadas nativas de Bootstrap (rounded-4) */
    <section className="py-4 rounded-4" style={{ backgroundColor: 'var(--crema-oscura)' }}>
      <div className="text-center mb-4 px-3">
        {/* Usamos el clamp() directamente en línea para no perder el título adaptativo */}
        <h2 className="mb-1" style={{ fontFamily: 'var(--font-display)', color: 'var(--verde-bosque)', fontWeight: '700', fontSize: 'clamp(1.6rem, 2.5vw, 2.2rem)' }}>
          Rutas Destacadas
        </h2>
        <p className="text-muted small mx-auto mb-0" style={{ maxWidth: '500px' }}>
          Explora los itinerarios históricos más emblemáticos del Camino de Santiago y planifica tu marcha.
        </p>
      </div>

      {/* Grid responsivo de Bootstrap 5 (Añadimos px-3 para que las tarjetas no se peguen al fondo crema en móvil) */}
      <div className="row g-3 justify-content-center px-3 m-0">
        {rutas.map((ruta) => (
          <div key={ruta.id} className="col-12 col-md-6 col-lg-4">
            <RouteCard ruta={ruta} />
          </div>
        ))}
      </div>

      <div className="text-center mt-4">
        <button 
          className="btn btn-outline-success px-4 fw-semibold btn-sm"
          onClick={() => navigate('/rutas')}
          style={{ borderRadius: 'var(--radius-md)' }}
        >
          Ver todas las rutas
        </button>
      </div>
    </section>
  );
}