import React, { useState, useEffect } from 'react';

export default function AdminRutaModal({ isOpen, onClose, onSave, rutaEditando }) {
  const [form, setForm] = useState({
    nombre: '', descripcion: '', distancia_total: '', tiempo_estimado: '', dificultad: 'Media', imagen: ''
  });

  useEffect(() => {
    if (rutaEditando) {
      setForm(rutaEditando);
    } else {
      setForm({ nombre: '', descripcion: '', distancia_total: '', tiempo_estimado: '', dificultad: 'Media', imagen: '' });
    }
  }, [rutaEditando, isOpen]);

  if (!isOpen) return null;

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(form);
  };

  return (
    <div className="modal show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
      <div className="modal-dialog modal-dialog-centered modal-lg">
        <div className="modal-content border-0 shadow" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="modal-header border-0 bg-light">
            <h5 className="modal-title fw-bold" style={{ color: 'var(--verde-bosque)' }}>
              {rutaEditando ? '📝 Editar Ruta' : '🎒 Nueva Ruta Senderista'}
            </h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body p-4">
              <div className="row g-3">
                <div className="col-12">
                  <label className="form-label fw-semibold small">Nombre de la Ruta</label>
                  <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} required required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12">
                  <label className="form-label fw-semibold small">Descripción Oficial</label>
                  <textarea name="descripcion" className="form-control" rows="3" value={form.descripcion} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }}></textarea>
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Distancia Total (km)</label>
                  <input type="number" step="0.1" name="distancia_total" className="form-control" value={form.distancia_total} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Tiempo Estimado (ej. 5 días / 6h)</label>
                  <input type="text" name="tiempo_estimado" className="form-control" value={form.tiempo_estimado} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Dificultad</label>
                  <select name="dificultad" className="form-select" value={form.dificultad} onChange={handleChange} style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                  </select>
                </div>
              </div>
            </div>
            <div className="modal-footer border-0 bg-light d-flex gap-2">
              <button type="button" className="btn btn-sm btn-outline-secondary px-4 fw-semibold" onClick={onClose} style={{ borderRadius: 'var(--radius-md)' }}>Cancelar</button>
              <button type="submit" className="btn btn-sm text-white px-4 fw-bold" style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}>Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}