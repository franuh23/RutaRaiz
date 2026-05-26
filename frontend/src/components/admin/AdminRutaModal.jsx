import React, { useState, useEffect } from 'react';

export default function AdminRutaModal({ isOpen, onClose, onSave, rutaEditando }) {
  const [form, setForm] = useState({
    nombre: '',
    descripcion: '',
    inicio: '',
    fin: '',
    kilometros: '',
    dificultad: 'media', // Por defecto en minúsculas
    imagen: ''
  });

  useEffect(() => {
    if (rutaEditando) {
      setForm({
        nombre: rutaEditando.nombre || '',
        descripcion: rutaEditando.descripcion || '',
        inicio: rutaEditando.inicio || '',
        fin: rutaEditando.fin || '',
        kilometros: rutaEditando.kilometros || '',
        dificultad: rutaEditando.dificultad || 'media',
        imagen: rutaEditando.imagen || ''
      });
    } else {
      setForm({
        nombre: '',
        descripcion: '',
        inicio: '',
        fin: '',
        kilometros: '',
        dificultad: 'media',
        imagen: ''
      });
    }
  }, [rutaEditando, isOpen]);

  if (!isOpen) return null;

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const dataToSend = {
      ...form,
      kilometros: parseFloat(form.kilometros),
      dificultad: form.dificultad.toLowerCase() // Asegura minúsculas obligatorias para Laravel
    };
    onSave(dataToSend);
  };

  return (
    <div className="modal show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050 }}>
      <div className="modal-dialog modal-dialog-centered modal-lg">
        <div className="modal-content border-0 shadow" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="modal-header border-0 bg-light">
            <h5 className="modal-title fw-bold" style={{ color: 'var(--verde-bosque)' }}>
              {rutaEditando ? '📝 Editar Ruta Oficial' : '🎒 Nueva Ruta Senderista'}
            </h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body p-4">
              <div className="row g-3">
                
                {/* Nombre */}
                <div className="col-12 col-md-8">
                  <label className="form-label fw-semibold small">Nombre de la Ruta</label>
                  <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Dificultad */}
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Dificultad</label>
                  <select name="dificultad" className="form-select" value={form.dificultad} onChange={handleChange} style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="baja">🟢 Baja</option>
                    <option value="media">🟡 Media</option>
                    <option value="alta">🔴 Alta</option>
                  </select>
                </div>

                {/* Inicio */}
                <div className="col-12 col-sm-4">
                  <label className="form-label fw-semibold small">Punto de Inicio</label>
                  <input type="text" name="inicio" className="form-control" value={form.inicio} onChange={handleChange} required placeholder="Ej. Oviedo" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Fin */}
                <div className="col-12 col-sm-4">
                  <label className="form-label fw-semibold small">Punto de Fin</label>
                  <input type="text" name="fin" className="form-control" value={form.fin} onChange={handleChange} required placeholder="Ej. Santiago de Compostela" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Kilómetros */}
                <div className="col-12 col-sm-4">
                  <label className="form-label fw-semibold small">Kilómetros</label>
                  <input type="number" step="0.1" name="kilometros" className="form-control" value={form.kilometros} onChange={handleChange} required placeholder="Ej. 321.5" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* URL Imagen */}
                <div className="col-12">
                  <label className="form-label fw-semibold small">URL de la Imagen <span className="text-muted fw-normal">(Opcional)</span></label>
                  <input type="text" name="imagen" className="form-control" value={form.imagen} onChange={handleChange} placeholder="https://ejemplo.com/imagen.jpg" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Descripción */}
                <div className="col-12">
                  <label className="form-label fw-semibold small">Descripción <span className="text-muted fw-normal">(Opcional)</span></label>
                  <textarea name="descripcion" className="form-control" rows="3" value={form.descripcion} onChange={handleChange} placeholder="Breve reseña sobre el camino..." style={{ borderRadius: 'var(--radius-md)' }}></textarea>
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