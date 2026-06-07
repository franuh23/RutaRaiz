import React, { useState, useEffect } from 'react';
// Modal de administración para crear o modificar hitos geográficos. Recibe las rutas disponibles,
// los datos a editar (locEditando) y devuelve los campos formateados numéricamente al componente padre a través de onSave.

export default function AdminLocalizacionModal({ isOpen, onClose, onSave, locEditando, rutas }) {
  const [form, setForm] = useState({
    ruta_id: '', 
    nombre: '', 
    descripcion: '', 
    distancia_desde_inicio: '', 
    distancia_desde_fin: ''
  });

  useEffect(() => {
    if (locEditando) {
      setForm({
        ruta_id: locEditando.ruta_id || '',
        nombre: locEditando.nombre || '',
        descripcion: locEditando.descripcion || '',
        distancia_desde_inicio: locEditando.distancia_desde_inicio || '',
        distancia_desde_fin: locEditando.distancia_desde_fin || ''
      });
    } else {
      setForm({ 
        ruta_id: rutas[0]?.id || '', 
        nombre: '', 
        descripcion: '', 
        distancia_desde_inicio: '', 
        distancia_desde_fin: '' 
      });
    }
  }, [locEditando, isOpen, rutas]);

  if (!isOpen) return null;

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const dataToSend = {
      ...form,
      distancia_desde_inicio: parseFloat(form.distancia_desde_inicio),
      distancia_desde_fin: parseFloat(form.distancia_desde_fin)
    };
    onSave(dataToSend);
  };

  return (
    <div className="modal show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050 }}>
      <div className="modal-dialog modal-dialog-centered modal-lg">
        <div className="modal-content border-0 shadow" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="modal-header border-0 bg-light">
            <h5 className="modal-title fw-bold" style={{ color: 'var(--verde-bosque)' }}>
              {locEditando ? '📝 Editar Punto de Paso' : '📍 Agregar Hito Geográfico'}
            </h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body p-4">
              <div className="row g-3">
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Asignar a Ruta</label>
                  <select name="ruta_id" className="form-select" value={form.ruta_id} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="">Selecciona una ruta destino</option>
                    {rutas.map(r => (
                      <option key={r.id} value={r.id}>{r.nombre}</option>
                    ))}
                  </select>
                </div>
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Nombre del Lugar</label>
                  <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                
                <div className="col-12 col-sm-6">
                  <label className="form-label fw-semibold small">Km desde el inicio</label>
                  <input type="number" step="0.1" name="distancia_desde_inicio" className="form-control" value={form.distancia_desde_inicio} onChange={handleChange} required placeholder="Ej: 12.5" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12 col-sm-6">
                  <label className="form-label fw-semibold small">Km hasta el final</label>
                  <input type="number" step="0.1" name="distancia_desde_fin" className="form-control" value={form.distancia_desde_fin} onChange={handleChange} required placeholder="Ej: 100.2" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                <div className="col-12">
                  <label className="form-label fw-semibold small">Descripción del Punto / Servicios (Opcional)</label>
                  <textarea name="descripcion" className="form-control" rows="3" value={form.descripcion} onChange={handleChange} style={{ borderRadius: 'var(--radius-md)' }}></textarea>
                </div>
              </div>
            </div>
            <div className="modal-footer border-0 bg-light">
              <button type="button" className="btn btn-sm btn-outline-secondary px-4 fw-semibold" onClick={onClose} style={{ borderRadius: 'var(--radius-md)' }}>Cancelar</button>
              <button type="submit" className="btn btn-sm text-white px-4 fw-bold" style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}>Guardar Punto</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}