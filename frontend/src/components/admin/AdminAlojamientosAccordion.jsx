import React, { useState } from 'react';
import AdminAlojamientoRow from './AdminAlojamientoRow';

export default function AdminAlojamientosAccordion({ rutas, localizaciones, alojamientos, onEditar, onEliminar }) {
  const [rutaAbierta, setRutaAbierta] = useState(null);
  const [locAbierta, setLocAbierta] = useState(null);

  return (
    <div className="accordion d-flex flex-column gap-3">
      {rutas.map((ruta) => {
        const locsDeEstaRuta = localizaciones.filter(loc => loc.ruta_id === ruta.id);
        const isRutaOpen = rutaAbierta === ruta.id;

        return (
          <div key={ruta.id} className="card border shadow-sm" style={{ borderRadius: 'var(--radius-md)', overflow: 'hidden' }}>
            {/* Nivel 1: Cabecera de Ruta */}
            <div 
              className="p-3 bg-white d-flex justify-content-between align-items-center"
              style={{ cursor: 'pointer' }}
              onClick={() => { setRutaAbierta(isRutaOpen ? null : ruta.id); setLocAbierta(null); }}
            >
              <div className="d-flex align-items-center gap-2">
                <span className="fs-5">🗺️</span>
                <h5 className="m-0 fw-bold text-dark">{ruta.nombre}</h5>
              </div>
              <span className="fw-bold text-muted">{isRutaOpen ? '🔼' : '🔽'}</span>
            </div>

            {/* Nivel 2: Desplegable de Localizaciones dentro de la Ruta */}
            {isRutaOpen && (
              <div className="card-body bg-light p-3 border-top d-flex flex-column gap-2">
                {locsDeEstaRuta.length === 0 ? (
                  <p className="text-muted text-center m-0 py-2">Esta ruta no tiene puntos geográficos creados.</p>
                ) : (
                  locsDeEstaRuta.map((loc) => {
                    // Filtrar alojamientos que pertenecen a esta localización concreta
                    const alojDeEstaLoc = alojamientos.filter(aloj => aloj.localizacion_id === loc.id);
                    const isLocOpen = locAbierta === loc.id;

                    return (
                      <div key={loc.id} className="card border-0 shadow-sm bg-white" style={{ borderRadius: 'var(--radius-sm)', overflow: 'hidden' }}>
                        <div 
                          className="p-2.5 px-3 d-flex justify-content-between align-items-center bg-white border-bottom"
                          style={{ cursor: 'pointer', fontSize: '0.95rem' }}
                          onClick={() => setLocAbierta(isLocOpen ? null : loc.id)}
                        >
                          <div className="fw-semibold text-secondary">
                            📍 {loc.nombre}{' '}
                            <span className="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-2 small" style={{ fontSize: '0.75rem' }}>
                              {alojDeEstaLoc.length} alojamientos
                            </span>
                          </div>
                          <span className="small">{isLocOpen ? '➖' : '➕'}</span>
                        </div>

                        {/* Nivel 3: Tabla con los alojamientos del pueblo */}
                        {isLocOpen && (
                          <div className="bg-light p-0">
                            {alojDeEstaLoc.length === 0 ? (
                              <p className="text-muted small m-0 p-3 text-center">No hay alojamientos registrados en este hito.</p>
                            ) : (
                              <div className="table-responsive">
                                <table className="table table-hover m-0 table-sm align-middle bg-white">
                                  <thead className="table-light text-uppercase x-small text-muted" style={{ fontSize: '0.75rem' }}>
                                    <tr>
                                      <th className="ps-3">ID</th><th>Establecimiento</th><th>Tipo</th><th>Precio Noche</th><th>Capacidad</th><th className="text-end pe-3">Acciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    {alojDeEstaLoc.map(aloj => (
                                      <AdminAlojamientoRow key={aloj.id} alojamiento={aloj} onEditar={onEditar} onEliminar={onEliminar} />
                                    ))}
                                  </tbody>
                                </table>
                              </div>
                            )}
                          </div>
                        )}
                      </div>
                    );
                  })
                )}
              </div>
            )}
          </div>
        );
      })}
    </div>
  );
}