import React from 'react';
import { useNavigate } from 'react-router-dom';
import RouteCard from '../ui/RouteCard';
import styles from './RoutesPreviewSection.module.css';

export default function RoutesPreviewSection({ rutas }) {
  const navigate = useNavigate();

  return (
    <section className={styles.section}>
      <div className="text-center mb-4">
        <h2 className={styles.title} style={{ fontFamily: 'var(--font-display)', color: 'var(--verde-bosque)', fontWeight: '700' }}>
          Rutas Destacadas
        </h2>
        <p className="text-muted small mx-auto" style={{ maxWidth: '500px' }}>
          Explora los itinerarios históricos más emblemáticos del Camino de Santiago y planifica tu marcha.
        </p>
      </div>

      {/* Grid responsivo de Bootstrap 5 */}
      <div className="row g-3 justify-content-center">
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