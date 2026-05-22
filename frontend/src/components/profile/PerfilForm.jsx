import React, { useState, useEffect } from 'react';
import Button from '../ui/Button';

export default function PerfilForm({ user, onUpdate }) {
  const [form, setForm] = useState({
    nombre: user?.nombre || '',
    apellidos: user?.apellidos || '',
    nick: user?.nick || '',
    email: user?.email || '',
    password: '',
    password_confirmation: '',
    avatar: null
  });
  const [preview, setPreview] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (user) {
      setForm(prev => ({
        ...prev,
        nombre: user.nombre || '',
        apellidos: user.apellidos || '',
        nick: user.nick || '',
        email: user.email || ''
      }));
    }
  }, [user]);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setForm({ ...form, avatar: file });
      setPreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    await onUpdate(form);
    setLoading(false);
    setForm(prev => ({ ...prev, password: '', password_confirmation: '' }));
  };

  return (
    <div className="card shadow-sm border-0 p-4" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h3 className="h5 fw-bold mb-4" style={{ color: 'var(--verde-bosque)' }}>
        ⚙️ Configuración del Perfil
      </h3>

      <form onSubmit={handleSubmit}>
        <div className="row g-3">
          {/* Selector de Avatar */}
          <div className="col-12 d-flex align-items-center gap-3 mb-2">
            {preview && (
              <img
                src={preview}
                alt="Previsualización"
                className="rounded-circle border"
                style={{ width: '60px', height: '60px', objectFit: 'cover' }}
              />
            )}
            <div className="w-100">
              <label className="form-label fw-semibold small m-0 mb-1">Foto de Perfil / Avatar</label>
              <input
                type="file"
                accept="image/*"
                className="form-control form-control-sm"
                onChange={handleFileChange}
                style={{ borderRadius: 'var(--radius-md)' }}
              />
            </div>
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Nombre</label>
            <input
              type="text"
              name="nombre"
              className="form-control"
              value={form.nombre}
              onChange={handleChange}
              required
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Apellidos</label>
            <input
              type="text"
              name="apellidos"
              className="form-control"
              value={form.apellidos}
              onChange={handleChange}
              required
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Nombre de Usuario (Nick)</label>
            <input
              type="text"
              name="nick"
              className="form-control"
              value={form.nick}
              onChange={handleChange}
              required
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Correo Electrónico</label>
            <input
              type="email"
              name="email"
              className="form-control"
              value={form.email}
              onChange={handleChange}
              required
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>

          <hr className="my-3 text-muted" />
          <p className="text-muted small m-0">Si no deseas cambiar tu contraseña, deja los campos en blanco.</p>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Nueva Contraseña</label>
            <input
              type="password"
              name="password"
              className="form-control"
              value={form.password}
              onChange={handleChange}
              placeholder="••••••••"
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>

          <div className="col-12 col-md-6">
            <label className="form-label fw-semibold small">Confirmar Nueva Contraseña</label>
            <input
              type="password"
              name="password_confirmation"
              className="form-control"
              value={form.password_confirmation}
              onChange={handleChange}
              placeholder="••••••••"
              style={{ borderRadius: 'var(--radius-md)' }}
            />
          </div>
        </div>

        <Button
          type="submit"
          variant="primary"
          size="md"
          className="w-100 mt-4 py-2 text-white"
          disabled={loading}
        >
          {loading ? 'Guardando...' : '💾 Guardar Cambios'}
        </Button>

      </form>
    </div>
  );
}