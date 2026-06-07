import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import Button from '../ui/Button';
import Badge from '../ui/Badge';
import { apiFetch } from '../../services/api';
// Tarjeta de itinerario personal.

export default function PlanificacionCard({ p, onVer, onEliminar, onEmpezar, activandoId }) {
  const { token } = useAuth();
  const [bajandoPdf, setBajandoPdf] = useState(false);
  const [bajandoExcel, setBajandoExcel] = useState(false);
  const [publicando, setPublicando] = useState(false);

  const {
    id,
    ruta_nombre = 'Camino de Santiago',
    fecha_inicio,
    km_dia = 20,
    dias_totales = 1,
    localizacion_inicio_nombre = 'Inicio',
    localizacion_fin_nombre = 'Fin de trayecto',
    is_public = false,
    es_clonada = false,
    en_curso = false
  } = p || {};

  const [isPublic, setIsPublic] = useState(is_public);

  const formatearFecha = (fechaStr) => {
    if (!fechaStr) return 'Fecha no definida';
    const fecha = new Date(fechaStr.replace(/-/g, '/'));
    if (isNaN(fecha.getTime())) return fechaStr;
    return fecha.toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  const handleTogglePublicar = async () => {
    if (!token || es_clonada) return;
    setPublicando(true);
    try {
      const nuevoEstado = !isPublic;
      const response = await apiFetch(`/api/planificaciones/${id}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ is_public: nuevoEstado })
      });
      const data = await response.json();
      if (response.ok) {
        setIsPublic(data.is_public);
        alert(data.message);
      } else {
        alert(data.message || 'No se pudo cambiar el estado de publicación.');
      }
    } catch (err) {
      console.error("Error al publicar/ocultar:", err);
      alert('Hubo un error de red al cambiar el estado de publicación.');
    } finally {
      setPublicando(false);
    }
  };

  const handleDescargarPdf = async () => {
    if (!token) return;
    setBajandoPdf(true);
    try {
      const response = await apiFetch(`/api/planificaciones/${id}/pdf`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      });
      if (!response.ok) throw new Error('No se pudo generar el documento PDF');
      const blob = await response.blob();
      const urlDescarga = window.URL.createObjectURL(blob);
      const enlaceFantasma = document.createElement('a');
      enlaceFantasma.href = urlDescarga;
      enlaceFantasma.download = `Itinerario_${ruta_nombre.replace(/\s+/g, '_')}.pdf`;
      document.body.appendChild(enlaceFantasma);
      enlaceFantasma.click();
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
      const response = await apiFetch(`/api/planificaciones/${id}/excel`, {
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
    <div className={`card shadow-sm border-0 p-3 mb-3 bg-white ${en_curso ? 'border-start border-success border-4' : ''}`} style={{ borderRadius: 'var(--radius-lg)' }}>
      <div className="row align-items-center g-3">
        <div className="col-12 col-md-6">
          <div className="d-flex align-items-center gap-2 mb-2">
            <h3 className="h5 mb-0" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
              {ruta_nombre}
            </h3>
            {isPublic && <span className="badge bg-success" style={{ fontSize: '10px' }}>🌐 Pública</span>}
            {en_curso && <span className="badge bg-primary" style={{ fontSize: '10px' }}>🧭 En Curso</span>}
          </div>

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

        <div className="col-12 col-md-6 d-flex justify-content-md-end align-items-center flex-wrap gap-2">
          {onEmpezar && (
            en_curso ? (
              <Button variant="secondary" size="sm" className="px-3 fw-bold" style={{ fontSize: '11px', background: '#e8f5e9', color: '#2e7d32', border: 'none', cursor: 'default' }}>
                🧭 EN CURSO
              </Button>
            ) : (
              <Button
                variant="primary"
                size="sm"
                className="px-3 fw-bold"
                style={{ fontSize: '11px', background: 'var(--verde-bosque)' }}
                disabled={activandoId === id}
                onClick={() => onEmpezar && onEmpezar(id)}
              >
                {activandoId === id ? <span className="spinner-border spinner-border-sm"></span> : '🏁 EMPEZAR'}
              </Button>
            )
          )}

          {es_clonada ? (
            <button className="btn btn-sm btn-light d-flex align-items-center gap-1 px-2" disabled style={{ borderRadius: 'var(--radius-md)', fontSize: '11px', fontWeight: '600', color: '#9e9e9e', cursor: 'not-allowed' }}>
              <i className="fa-solid fa-lock" style={{ color: '#b0bec5' }}></i>
              <span>Guardada</span>
            </button>
          ) : (
            <button className="btn btn-sm d-flex align-items-center gap-2 px-3" onClick={handleTogglePublicar} disabled={publicando} style={{ fontWeight: '700', borderRadius: 'var(--radius-md)', border: isPublic ? '2px solid var(--verde-medio)' : '2px solid #78909c', color: isPublic ? 'var(--verde-medio)' : '#78909c', backgroundColor: isPublic ? 'rgba(74, 114, 85, 0.05)' : 'transparent', transition: 'all 0.2s ease', fontSize: '11px' }}>
              {publicando ? <span className="spinner-border spinner-border-sm"></span> : <i className={`fa-solid ${isPublic ? 'fa-eye' : 'fa-eye-slash'}`}></i>}
              <span>{isPublic ? 'PÚBLICA' : 'HACER PÚBLICA'}</span>
            </button>
          )}

          <button className="btn btn-sm d-flex align-items-center gap-2 px-3" onClick={handleDescargarPdf} disabled={bajandoPdf} style={{ fontWeight: '700', borderRadius: 'var(--radius-md)', border: '2px solid #e53935', color: '#e53935', backgroundColor: 'transparent', transition: 'all 0.2s ease', fontSize: '11px' }}>
            {bajandoPdf ? <span className="spinner-border spinner-border-sm"></span> : <i className="fa-solid fa-file-pdf" style={{ fontSize: '14px' }}></i>}
            <span>⤓ PDF</span>
          </button>

          <button className="btn btn-sm d-flex align-items-center gap-2 px-3" onClick={handleDescargarExcel} disabled={bajandoExcel} style={{ fontWeight: '700', borderRadius: 'var(--radius-md)', border: '2px solid #1b5e20', color: '#1b5e20', backgroundColor: 'transparent', transition: 'all 0.2s ease', fontSize: '11px' }}>
            {bajandoExcel ? <span className="spinner-border spinner-border-sm"></span> : <i className="fa-solid fa-file-excel" style={{ fontSize: '14px' }}></i>}
            <span>⤓ EXCEL</span>
          </button>

          <Button variant="outline" size="sm" onClick={() => onVer && onVer(id)} className="px-3 fw-bold" style={{ fontSize: '11px' }}>
            VER ETAPAS
          </Button>

          <button className="btn btn-sm btn-outline-danger px-3 fw-bold" onClick={() => onEliminar && onEliminar(id)} style={{ borderRadius: 'var(--radius-md)', borderWidth: '2px', fontSize: '11px' }}>
            ELIMINAR
          </button>
        </div>
      </div>
    </div>
  );
}