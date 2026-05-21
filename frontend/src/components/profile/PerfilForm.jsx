import React, { useState } from 'react';

export default function PerfilForm({ user, onUpdate }) {
  const [form, setForm] = useState({
    nombre: user?.nombre || '',
    apellidos: user?.apellidos || '',
    password: '',
    password_confirmation: ''
  });
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    await onUpdate(form);
    setLoading(false);
    setForm({ ...form, password: '', password_confirmation: '' }); // Limpiar campos de clave
  };

  return (
    <div className="card shadow-sm border-0 p-4" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h3 className="h5 fw-bold mb-4" style={{ color: 'var(--verde-bosque)' }}>
        ⚙️ Actualizar Perfil
      </h3>
      
      <form onSubmit={handleSubmit}>
        <div className="row g-3">
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

          <hr className="my-4 text-muted" />
          <p className="text-muted small m-0">Si no deseas cambiar tu contraseña, deja los siguientes campos en blanco.</p>

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

        <button 
          type="submit" 
          className="btn text-white fw-bold w-100 mt-4 py-2" 
          disabled={loading}
          style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}
        >
          {loading ? 'Guardando...' : 'Guardar Cambios'}
        </button>
      </form>
    </div>
  );
}