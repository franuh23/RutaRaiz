import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import Button from '../ui/Button';
import Badge from '../ui/Badge';

export default function PlanificacionCard({ p, onVer, onEliminar }) {
  const { token } = useAuth();
  const [bajandoPdf, setBajandoPdf] = useState(false);
  const [bajandoExcel, setBajandoExcel] = useState(false);

  const {
    id,
    ruta_nombre = 'Camino de Santiago',
    fecha_inicio,
    km_dia = 20,
    dias_totales = 1,
    localizacion_inicio_nombre = 'Inicio',
    localizacion_fin_nombre = 'Fin de trayecto'
  } = p || {};

  // Formateador de fecha
  const formatearFecha = (fechaStr) => {
    if (!fechaStr) return 'Fecha no definida';
    const fecha = new Date(fechaStr.replace(/-/g, '/'));
    if (isNaN(fecha.getTime())) return fechaStr;
    return fecha.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  // Descarga el PDF binario desde la API
  const handleDescargarPdf = async () => {
    if (!token) return;
    setBajandoPdf(true);

    try {
      const response = await fetch(`/api/planificaciones/${id}/pdf`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error('No se pudo generar el documento PDF');
      }

      const blob = await response.blob();

      // Creamos una URL temporal apuntando a ese objeto en la memoria del navegador
      const urlDescarga = window.URL.createObjectURL(blob);

      // Creamos un enlace <a> fantasma en el DOM, hacemos click y lo destruimos
      const enlaceFantasma = document.createElement('a');
      enlaceFantasma.href = urlDescarga;
      enlaceFantasma.download = `Itinerario_${ruta_nombre.replace(/\s+/g, '_')}.pdf`;
      document.body.appendChild(enlaceFantasma);
      enlaceFantasma.click();

      // Limpieza de memoria
      document.body.removeChild(enlaceFantasma);
      window.URL.revokeObjectURL(urlDescarga);

    } catch (err) {
      console.error("Error al descargar el PDF:", err);
      alert('Hubo un error al intentar generar o descargar tu PDF.');
    } finally {
      setBajandoPdf(false);
    }
  };

  const handleDescargarExcel = async () => {
    if (!token) return;
    setBajandoExcel(true);
    try {
      const response = await fetch(`/api/planificaciones/${id}/excel`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });

      if (!response.ok) throw new Error('Error al generar Excel');

      const blob = await response.blob();
      const urlDescarga = window.URL.createObjectURL(blob);
      const enlaceFantasma = document.createElement('a');
      enlaceFantasma.href = urlDescarga;
      enlaceFantasma.download = `Itinerario_${ruta_nombre.replace(/\s+/g, '_')}.xlsx`;
      document.body.appendChild(enlaceFantasma);
      enlaceFantasma.click();
      document.body.removeChild(enlaceFantasma);
      window.URL.revokeObjectURL(urlDescarga);
    } catch (err) {
      console.error(err);
      alert('Hubo un error al descargar tu Excel.');
    } finally {
      setBajandoExcel(false);
    }
  };

  return (
    <div className="card shadow-sm border-0 p-3 mb-3 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="row align-items-center g-3">
        {/* Lado izquierdo: Información del itinerario */}
        <div className="col-12 col-md-7">
          <h3 className="h5 mb-2" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
            {ruta_nombre}
          </h3>

          <div className="d-flex flex-wrap gap-2 align-items-center mb-3">
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

        {/* Lado derecho: Acciones de la tarjeta */}
<div className="col-12 col-md-5 d-flex justify-content-md-end flex-wrap gap-2">
  
  {/* Botón de PDF - Estilo Adobe Corporativo */}
  <button 
    className="btn btn-sm d-flex align-items-center gap-2 px-3"
    onClick={handleDescargarPdf}
    disabled={bajandoPdf}
    style={{ 
      fontWeight: '700', 
      borderRadius: 'var(--radius-md)', 
      border: '2px solid #e53935', // Rojo Adobe
      color: '#e53935',
      backgroundColor: 'transparent',
      transition: 'all 0.2s ease',
      fontSize: '12px'
    }}
    onMouseEnter={(e) => {
      e.currentTarget.style.backgroundColor = '#e53935';
      e.currentTarget.style.color = '#fff';
    }}
    onMouseLeave={(e) => {
      e.currentTarget.style.backgroundColor = 'transparent';
      e.currentTarget.style.color = '#e53935';
    }}
  >
    {bajandoPdf ? (
      <span className="spinner-border spinner-border-sm" role="status"></span>
    ) : (
      <i className="fa-solid fa-file-pdf" style={{ fontSize: '14px' }}></i>
    )}
    <span>⤓ PDF</span>
  </button>

  {/* Botón de Excel - Estilo Microsoft Emerald */}
  <button 
    className="btn btn-sm d-flex align-items-center gap-2 px-3"
    onClick={handleDescargarExcel}
    disabled={bajandoExcel}
    style={{ 
      fontWeight: '700', 
      borderRadius: 'var(--radius-md)', 
      border: '2px solid #1b5e20', // Verde Excel
      color: '#1b5e20',
      backgroundColor: 'transparent',
      transition: 'all 0.2s ease',
      fontSize: '12px'
    }}
    onMouseEnter={(e) => {
      e.currentTarget.style.backgroundColor = '#1b5e20';
      e.currentTarget.style.color = '#fff';
    }}
    onMouseLeave={(e) => {
      e.currentTarget.style.backgroundColor = 'transparent';
      e.currentTarget.style.color = '#1b5e20';
    }}
  >
    {bajandoExcel ? (
      <span className="spinner-border spinner-border-sm" role="status"></span>
    ) : (
      <i className="fa-solid fa-file-excel" style={{ fontSize: '14px' }}></i>
    )}
    <span>⤓ EXCEL</span>
  </button>

  {/* El botón de Ver etapas lo mantenemos como el principal de acción */}
  <Button 
    variant="outline" 
    size="sm" 
    onClick={() => onVer && onVer(id)}
    className="px-3 fw-bold"
  >
    VER ETAPAS
  </Button>
  
  {/* El botón de Eliminar se queda igual para mantener la coherencia de peligro */}
  <button 
    className="btn btn-sm btn-outline-danger px-3 fw-bold" 
    onClick={() => onEliminar && onEliminar(id)}
    style={{ borderRadius: 'var(--radius-md)', borderWidth: '2px' }}
  >
    ELIMINAR
  </button>
</div>
      </div>
    </div>
  );
}