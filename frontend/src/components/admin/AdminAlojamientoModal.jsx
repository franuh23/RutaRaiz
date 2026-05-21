import React, { useState, useEffect } from 'react';

export default function AdminAlojamientoModal({ isOpen, onClose, onSave, alojamientoEditando, localizaciones }) {
  const [form, setForm] = useState({
    localizacion_id: '', nombre: '', descripcion: '', tipo: 'Albergue', precio_noche: '', plazas_totales: '', contacto: '', imagen: ''
  });

  useEffect(() => {
    if (alojamientoEditando) {
      setForm(alojamientoEditando);
    } else {
      setForm({ localizacion_id: localizaciones[0]?.id || '', nombre: '', descripcion: '', tipo: 'Albergue', precio_noche: '', plazas_totales: '', contacto: '', imagen: '' });
    }
  }, [alojamientoEditando, isOpen, localizaciones]);

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
              {alojamientoEditando ? '📝 Editar Alojamiento' : '🏠 Nuevo Establecimiento / Albergue'}
            </h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body p-4">
              <div className="row g-3">
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Ubicación / Localización Hito</label>
                  <select name="localizacion_id" className="form-select" value={form.localizacion_id} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="">Selecciona un punto de paso</option>
                    {localizaciones.map(l => (
                      <option key={l.id} value={l.id}>{l.nombre}</option>
                    ))}
                  </select>
                </div>
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Nombre Comercial</label>
                  <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12">
                  <label className="form-label fw-semibold small">Descripción de Servicios e Instalaciones</label>
                  <textarea name="descripcion" className="form-control" rows="2" value={form.descripcion} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }}></textarea>
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Tipo de Establecimiento</label>
                  <select name="tipo" className="form-select" value={form.tipo} onChange={handleChange} style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="Albergue">Albergue Municipal / Privado</option>
                    <option value="Hostal">Hostal / Pensión</option>
                    <option value="Hotel">Hotel Rural</option>
                  </select>
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Precio por Noche (€)</label>
                  <input type="number" step="0.01" name="precio_noche" className="form-control" value={form.precio_noche} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Plazas Totales Disponibles</label>
                  <input type="number" name="plazas_totales" className="form-control" value={form.plazas_totales} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>
              </div>
            </div>
            <div className="modal-footer border-0 bg-light">
              <button type="button" className="btn btn-sm btn-outline-secondary px-4 fw-semibold" onClick={onClose} style={{ borderRadius: 'var(--radius-md)' }}>Cancelar</button>
              <button type="submit" className="btn btn-sm text-white px-4 fw-bold" style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}>Guardar Establecimiento</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}