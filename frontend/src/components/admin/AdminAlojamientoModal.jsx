import React, { useState, useEffect } from 'react';

export default function AdminAlojamientoModal({ isOpen, onClose, onSave, alojamientoEditando, localizaciones }) {
  const [form, setForm] = useState({
    localizacion_id: '',
    nombre: '',
    direccion: '',
    tipo: 'albergue', // Inicializado con el enum en minúsculas
    enlace: '',
    telefono: '',
    email: '',
    imagen: ''
  });

  useEffect(() => {
    if (alojamientoEditando) {
      setForm({
        localizacion_id: alojamientoEditando.localizacion_id || '',
        nombre: alojamientoEditando.nombre || '',
        direccion: alojamientoEditando.direccion || '',
        tipo: alojamientoEditando.tipo || 'albergue',
        enlace: alojamientoEditando.enlace || '',
        telefono: alojamientoEditando.telefono || '',
        email: alojamientoEditando.email || '',
        imagen: alojamientoEditando.imagen || ''
      });
    } else {
      setForm({
        localizacion_id: localizaciones[0]?.id || '',
        nombre: '',
        direccion: '',
        tipo: 'albergue',
        enlace: '',
        telefono: '',
        email: '',
        imagen: ''
      });
    }
  }, [alojamientoEditando, isOpen, localizaciones]);

  if (!isOpen) return null;

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Limpiamos los strings vacíos que sean opcionales a null por cortesía con la BD
    const dataToSend = {
      ...form,
      tipo: form.tipo.toLowerCase(), // Aseguramos minúsculas para Laravel rules
      direccion: form.direccion || null,
      enlace: form.enlace || null,
      telefono: form.telefono || null,
      email: form.email || null,
      imagen: form.imagen || null
    };
    onSave(dataToSend);
  };

  return (
    <div className="modal show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050 }}>
      <div className="modal-dialog modal-dialog-centered modal-lg">
        <div className="modal-content border-0 shadow" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="modal-header border-0 bg-light">
            <h5 className="modal-title fw-bold" style={{ color: 'var(--verde-bosque)' }}>
              {alojamientoEditando ? '📝 Editar Alojamiento Oficial' : '🏠 Nuevo Establecimiento / Albergue'}
            </h5>
            <button type="button" className="btn-close" onClick={onClose}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body p-4">
              <div className="row g-3">
                
                {/* Localización */}
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Ubicación / Punto de Paso</label>
                  <select name="localizacion_id" className="form-select" value={form.localizacion_id} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="">Selecciona un punto de paso</option>
                    {localizaciones.map(l => (
                      <option key={l.id} value={l.id}>{l.nombre}</option>
                    ))}
                  </select>
                </div>

                {/* Nombre */}
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Nombre Comercial</label>
                  <input type="text" name="nombre" className="form-control" value={form.nombre} onChange={handleChange} required style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Tipo de Establecimiento */}
                <div className="col-12 col-md-4">
                  <label className="form-label fw-semibold small">Tipo de Alojamiento</label>
                  <select name="tipo" className="form-select" value={form.tipo} onChange={handleChange} style={{ borderRadius: 'var(--radius-md)' }}>
                    <option value="albergue">🎒 Albergue</option>
                    <option value="hostal">🏨 Hostal / Pensión</option>
                    <option value="hotel">⭐ Hotel</option>
                    <option value="casa_rural">🏡 Casa Rural</option>
                    <option value="camping">⛺ Camping</option>
                  </select>
                </div>

                {/* Teléfono */}
                <div className="col-12 col-sm-4">
                  <label className="form-label fw-semibold small">Teléfono de Contacto</label>
                  <input type="text" name="telefono" className="form-control" value={form.telefono} onChange={handleChange} placeholder="Ej. 600123456" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Email */}
                <div className="col-12 col-sm-4">
                  <label className="form-label fw-semibold small">Email Reservas</label>
                  <input type="email" name="email" className="form-control" value={form.email} onChange={handleChange} placeholder="reservas@albergue.com" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Dirección Física */}
                <div className="col-12">
                  <label className="form-label fw-semibold small">Dirección Postal / Física</label>
                  <input type="text" name="direccion" className="form-control" value={form.direccion} onChange={handleChange} placeholder="Calle Mayor, Nº 14" style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Enlace Web / Booking */}
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">Sitio Web / Enlace Reservas</label>
                  <input type="url" name="enlace" className="form-control" value={form.enlace} onChange={handleChange} placeholder="https://www.booking.com/..." style={{ borderRadius: 'var(--radius-md)' }} />
                </div>

                {/* Imagen URL */}
                <div className="col-12 col-md-6">
                  <label className="form-label fw-semibold small">URL de Imagen Decorativa</label>
                  <input type="text" name="imagen" className="form-control" value={form.imagen} onChange={handleChange} placeholder="https://imagenes.com/albergue.jpg" style={{ borderRadius: 'var(--radius-md)' }} />
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