import React, { useState } from 'react';
import AdminLocalizacionRow from './AdminLocalizacionRow';

export default function AdminLocalizacionesAccordion({ rutas, localizaciones, onEditar, onEliminar }) {
  const [rutaAbierta, setRutaAbierta] = useState(null);

  const toggleRuta = (rutaId) => {
    setRutaAbierta(rutaAbierta === rutaId ? null : rutaId);
  };

  return (
    <div className="accordion d-flex flex-column gap-3">
      {rutas.map((ruta) => {
        // Filtrar las localizaciones que pertenecen a esta ruta concreta
        const locsDeEstaRuta = localizaciones.filter(loc => loc.ruta_id === ruta.id)
          .sort((a, b) => a.distancia_desde_inicio - b.distancia_desde_inicio);
        
        const isOpen = rutaAbierta === ruta.id;

        return (
          <div key={ruta.id} className="card border shadow-sm" style={{ borderRadius: 'var(--radius-md)', overflow: 'hidden' }}>
            {/* Cabecera del Acordeón */}
            <div 
              className="p-3 bg-white d-flex justify-content-between align-items-center"
              style={{ cursor: 'pointer' }}
              onClick={() => toggleRuta(ruta.id)}
            >
              <div className="d-flex align-items-center gap-2">
                <span className="fs-5">🗺️</span>
                <h5 className="m-0 fw-bold text-dark">{ruta.nombre}</h5>
                <span className="badge bg-light text-secondary border ms-2">
                  {locsDeEstaRuta.length} puntos de paso
                </span>
              </div>
              <span className="fw-bold text-muted">{isOpen ? '🔼' : '🔽'}</span>
            </div>

            {/* Cuerpo desplegable con la tabla filtrada */}
            {isOpen && (
              <div className="card-body bg-light p-0 border-top">
                {locsDeEstaRuta.length === 0 ? (
                  <p className="text-muted m-0 p-4 text-center">No hay localizaciones asignadas a esta ruta aún.</p>
                ) : (
                  <div className="table-responsive">
                    <table className="table table-hover m-0 bg-white">
                      <thead className="table-light text-uppercase small text-muted">
                        <tr>
                          <th className="ps-3">ID</th><th>Punto Geográfico</th><th>Descripción Hito</th><th>Hito Métrica</th><th className="text-end pe-3">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        {locsDeEstaRuta.map(loc => (
                          <AdminLocalizacionRow key={loc.id} loc={loc} onEditar={onEditar} onEliminar={onEliminar} />
                        ))}
                      </tbody>
                    </table>
                  </div>
                )}
              </div>
            )}
          </div>
        );
      })}
    </div>
  );
}