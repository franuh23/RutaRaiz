import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export default function RegisterForm() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    nick: '', nombre: '', apellidos: '', email: '',
    password: '', password_confirmation: ''
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (form.password !== form.password_confirmation) {
      setError('Las contraseñas no coinciden');
      return;
    }

    setLoading(true);
    try {
      const response = await fetch('/api/register', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json', 
          'Accept': 'application/json' 
        },
        body: JSON.stringify(form)
      });
      
      const data = await response.json();

      if (!response.ok) {
        // ✨ MODO PRO: Si Laravel devuelve errores de validación específicos, los unificamos limpiamente
        const msgs = data.errors
          ? Object.values(data.errors).flat().join(' ')
          : data.message || 'Error al procesar el registro.';
        throw new Error(msgs);
      }

      localStorage.setItem('token', data.access_token);
      await login(form.email, form.password);
      navigate('/');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="card shadow-sm border-0 p-4 my-4" style={{ maxWidth: '500px', width: '100%', borderRadius: 'var(--radius-lg)' }}>
      <h2 className="text-center mb-4" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
        Crear cuenta
      </h2>

      {error && (
        <div className="alert alert-danger py-2" role="alert" style={{ borderRadius: 'var(--radius-md)', fontSize: '0.9rem' }}>
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit}>
        {[
          { name: 'nick', label: 'Nombre de usuario (Nick)', type: 'text', placeholder: 'mi_nick' },
          { name: 'nombre', label: 'Nombre', type: 'text', placeholder: 'Juan' },
          { name: 'apellidos', label: 'Apellidos', type: 'text', placeholder: 'García López' },
          { name: 'email', label: 'Correo electrónico', type: 'email', placeholder: 'tu@email.com' },
          { name: 'password', label: 'Contraseña', type: 'password', placeholder: '••••••••' },
          { name: 'password_confirmation', label: 'Repetir contraseña', type: 'password', placeholder: '••••••••' },
        ].map(({ name, label, type, placeholder }) => (
          <div key={name} className="mb-3">
            <label className="form-label" style={{ color: 'var(--oscuro)', fontSize: '0.9rem', fontWeight: '600' }}>
              {label}
            </label>
            <input
              type={type}
              name={name}
              className="form-control"
              value={form[name]}
              onChange={handleChange}
              required
              placeholder={placeholder}
              style={{ borderRadius: 'var(--radius-md)', padding: '0.65rem' }}
            />
            {/* 💡 AVISO VISUAL DE SEGURIDAD EN EL CAMPO CONTRASEÑA */}
            {name === 'password' && (
              <div className="form-text text-muted" style={{ fontSize: '0.78rem', lineHeight: '1.3' }}>
                Requisito: Mínimo 8 caracteres, incluyendo letras, números y algún símbolo especial (ej. @, #, $, *, !).
              </div>
            )}
          </div>
        ))}

        <button 
          type="submit" 
          className="btn w-100 text-white fw-bold py-2 mt-2" 
          disabled={loading}
          style={{ 
            background: 'var(--verde-bosque)', 
            borderRadius: 'var(--radius-md)',
            transition: 'var(--transition)'
          }}
        >
          {loading ? 'Creando cuenta...' : 'Registrarse'}
        </button>
      </form>

      <p className="text-center mt-4 mb-0" style={{ fontSize: '0.9rem', color: 'var(--piedra)' }}>
        ¿Ya tienes cuenta? <Link to="/login" style={{ color: 'var(--verde-medio)', fontWeight: '600', textDecoration: 'underline' }}>Inicia sesión</Link>
      </p>
    </div>
  );
}