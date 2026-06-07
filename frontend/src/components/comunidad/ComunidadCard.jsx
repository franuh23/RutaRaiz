import React, { useState } from 'react';
import Badge from '../ui/Badge';
import EtapaCard from '../planificacion/EtapaCard'; // 🚀 Reutilizamos tu componente de etapas desplegables

const HeartLikeButton = ({ count, isActive, onClick }) => {
  return (
    <button
      onClick={onClick}
      className="d-flex align-items-center justify-content-center border-0 p-0 position-relative"
      style={{
        background: 'none',
        outline: 'none',
        cursor: 'pointer',
        transition: 'transform 0.2s ease',
        transform: isActive ? 'scale(1.1)' : 'scale(1)',
      }}
      onMouseEnter={(e) => { if (!isActive) e.currentTarget.style.transform = 'scale(1.05)'; }}
      onMouseLeave={(e) => { if (!isActive) e.currentTarget.style.transform = 'scale(1)'; }}
    >
      <svg
        width="60"
        height="50"
        viewBox="0 0 24 22"
        fill={isActive ? '#e91e63' : 'white'}
        stroke={isActive ? '#e91e63' : '#aab8c2'}
        strokeWidth="1.5"
        style={{
          transition: 'all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
          filter: isActive ? 'drop-shadow(0 2px 6px rgba(233, 30, 99, 0.3))' : 'none',
        }}
      >
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
      </svg>
      <span style={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%, -50%)', fontSize: '14px', fontWeight: '800', color: isActive ? 'white' : '#666', pointerEvents: 'none' }}>
        {count}
      </span>
    </button>
  );
};

export default function ComunidadCard({ p, onLike, onClonar, clonandoId, usuarioLogueadoId }) {
  const [showEtapas, setShowEtapas] = useState(false);

  const {
    id,
    ruta_nombre = 'Camino de Santiago',
    usuario_id,
    usuario_nick = 'Peregrino',
    fecha_inicio,
    km_dia = 20,
    dias_totales = 1,
    likes_count = 0,
    ha_dado_like = false,
    etapas = []
  } = p;

  const esMia = usuario_id === usuarioLogueadoId;

  const formatearFecha = (fechaStr) => {
    if (!fechaStr) return 'Fecha no definida';
    const fecha = new Date(fechaStr.replace(/-/g, '/'));
    if (isNaN(fecha.getTime())) return fechaStr;
    return fecha.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  return (
    <div className="card shadow-sm border-0 p-4 mb-3 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="row align-items-center g-3">
        {/* Info de la ruta */}
        <div className="col-12 col-md-8">
          <div className="d-flex align-items-center gap-2 mb-2">
            <h3 className="h5 mb-0" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
              {ruta_nombre}
            </h3>
            <span className="text-muted small">por <strong>@{usuario_nick}</strong></span>
            {esMia && <span className="badge bg-secondary" style={{ fontSize: '10px' }}>Tuya</span>}
          </div>

          <div className="d-flex flex-wrap gap-2 align-items-center">
            <Badge variant="default" size="sm">👣 {Math.round(km_dia)} km/día</Badge>
            <Badge variant="difficulty-medium" size="sm">🗓️ {dias_totales} {dias_totales === 1 ? 'día' : 'días'}</Badge>
            <span className="text-muted small ps-1">📅 Salida: {formatearFecha(fecha_inicio)}</span>
            
            <button 
              className="btn btn-sm px-3 py-1 ms-md-2 d-flex align-items-center gap-2"
              style={{ 
                fontSize: '12px', 
                fontWeight: '700',
                borderRadius: 'var(--radius-md)',
                border: showEtapas ? '2px solid var(--verde-bosque)' : '2px solid #b0bec5',
                color: showEtapas ? 'white' : '#546e7a',
                backgroundColor: showEtapas ? 'var(--verde-bosque)' : 'transparent',
                transition: 'all 0.2s ease'
              }}
              onClick={() => setShowEtapas(!showEtapas)}
            >
              <i className={`fa-solid ${showEtapas ? 'fa-eye-slash' : 'fa-eye'}`}></i>
              <span>{showEtapas ? 'Ocultar Etapas' : `Ver ${etapas.length} Etapas`}</span>
            </button>
          </div>
        </div>

        {/* Likes y Clonar */}
        <div className="col-12 col-md-4 d-flex justify-content-md-end align-items-center gap-3">
          <HeartLikeButton 
            count={likes_count}
            isActive={ha_dado_like}
            onClick={() => onLike(id)}
          />

          <button
            className="btn d-flex align-items-center gap-2 px-3 text-white"
            onClick={() => onClonar(id)}
            disabled={esMia || clonandoId === id}
            style={{
              fontWeight: '700',
              fontSize: '13px',
              borderRadius: '20px',
              backgroundColor: esMia ? '#e0e0e0' : 'var(--verde-bosque)',
              color: esMia ? '#9e9e9e' : '#fff',
              border: 'none',
              transition: 'all 0.2s ease-in-out',
              boxShadow: esMia ? 'none' : '0 2px 6px rgba(45, 90, 39, 0.2)',
              cursor: esMia ? 'not-allowed' : 'pointer',
              height: '42px',
              marginTop: '-5px'
            }}
            onMouseEnter={(e) => {
              if (!esMia && clonandoId !== id) {
                e.currentTarget.style.backgroundColor = '#23461e';
                e.currentTarget.style.transform = 'translateY(-1px)';
                e.currentTarget.style.boxShadow = '0 4px 12px rgba(45, 90, 39, 0.3)';
              }
            }}
            onMouseLeave={(e) => {
              if (!esMia && clonandoId !== id) {
                e.currentTarget.style.backgroundColor = 'var(--verde-bosque)';
                e.currentTarget.style.transform = 'translateY(0px)';
                e.currentTarget.style.boxShadow = '0 2px 6px rgba(45, 90, 39, 0.2)';
              }
            }}
          >
            {clonandoId === id ? (
              <span className="spinner-border spinner-border-sm" role="status"></span>
            ) : (
              <i className="fa-solid fa-route" style={{ fontSize: '14px' }}></i>
            )}
            <span>{esMia ? 'En tu mochila' : 'La quiero'}</span>
          </button>
        </div>
      </div>

      {/* LISTADO DE ETAPAS */}
      {showEtapas && (
        <div className="mt-4 pt-3 border-top animate__animated animate__fadeIn">
          <h5 className="h6 fw-bold text-muted text-uppercase mb-3" style={{ fontSize: '11px', letterSpacing: '0.05em' }}>
            🗺️ Desglose del itinerario de @{usuario_nick}:
          </h5>
          <div className="d-flex flex-column gap-1">
            {etapas.length > 0 ? (
              etapas.map((etapa) => (
                <EtapaCard key={etapa.dia} etapa={etapa} />
              ))
            ) : (
              <p className="text-muted small ps-2">No hay detalles cargados para este camino.</p>
            )}
          </div>
        </div>
      )}
    </div>
  );
}