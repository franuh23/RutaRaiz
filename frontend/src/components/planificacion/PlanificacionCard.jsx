import React from 'react';
import Button from '../ui/Button';
import Badge from '../ui/Badge';

export default function PlanificacionCard({ p, onVer, onEliminar }) {
  // Desestructuración segura con la barra baja idéntica a tu base de datos de PostgreSQL
  const {
    id,
    ruta_nombre = 'Camino de Santiago',
    fecha_inicio,
    km_dia = 20,
    dias_totales = 1,
    localizacion_inicio_nombre = 'Inicio',
    localizacion_fin_nombre = 'Fin de trayecto'
  } = p || {};

  // Formateador de fecha en castellano
  const formatearFecha = (fechaStr) => {
    if (!fechaStr) return 'Fecha no definida';
    const fecha = new Date(fechaStr.replace(/-/g, '/'));
    return fecha.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  return (
    <div className="card shadow-sm border-0 p-3 mb-3 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="row align-items-center g-3">
        {/* Lado izquierdo: Información del itinerario */}
        <div className="col-12 col-md-8">
          <h3 className="h5 mb-2" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
            {ruta_nombre}
          </h3>

          {/* Fila de Badges para estructurar las métricas de la ruta */}
          <div className="d-flex flex-wrap gap-2 align-items-center mb-3">
            {/* 🛠️ CORREGIDO: Ahora llamamos a km_dia con barra baja */}
            <Badge variant="default" size="sm">👣 {km_dia} km/día</Badge>
            <Badge variant="difficulty-medium" size="sm">🗓️ {dias_totales} {dias_totales === 1 ? 'día' : 'días'}</Badge>
            <span className="text-muted small ps-1">📅 Salida: {formatearFecha(fecha_inicio)}</span>
          </div>

          <div className="text-muted small d-flex align-items-center gap-1">
            <span className="text-dark fw-medium">📍 Tramo:</span>
            <span>{localizacion_inicio_nombre}</span>
            <span className="text-success">→</span>
            <span>{localizacion_fin_nombre || 'Final de ruta'}</span>
          </div>
        </div>

        {/* Lado derecho: Acciones usando tu Button.jsx */}
        <div className="col-12 col-md-4 d-flex justify-content-md-end gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => onVer && onVer(id)}
          >
            Ver etapas
          </Button>

          <button
            className="btn btn-sm btn-outline-danger px-3 border-2"
            onClick={() => onEliminar && onEliminar(id)}
            style={{ fontWeight: '600', borderRadius: 'var(--radius-md)' }}
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  );
}