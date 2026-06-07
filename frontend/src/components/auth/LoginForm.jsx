import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
// Formulario de login. Consume el contexto useAuth para autenticar al usuario y redirige al inicio mediante useNavigate.

export default function LoginForm() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      await login(email, password);
      navigate('/');
    } catch (err) {
      setError(err.message || 'Credenciales incorrectas');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="card shadow-sm border-0 p-4" style={{ maxWidth: '460px', width: '100%', borderRadius: 'var(--radius-lg)' }}>
      <h2 className="text-center mb-4" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
        Iniciar sesión
      </h2>

      {error && (
        <div className="alert alert-danger py-2" role="alert" style={{ borderRadius: 'var(--radius-md)', fontSize: '0.9rem' }}>
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label className="form-label" style={{ color: 'var(--oscuro)', fontSize: '0.9rem', fontWeight: '600' }}>
            Correo electrónico
          </label>
          <input
            type="email"
            className="form-control"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            placeholder="tu@email.com"
            style={{ borderRadius: 'var(--radius-md)', padding: '0.75rem' }}
          />
        </div>

        <div className="mb-4">
          <label className="form-label" style={{ color: 'var(--oscuro)', fontSize: '0.9rem', fontWeight: '600' }}>
            Contraseña
          </label>
          <input
            type="password"
            className="form-control"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
            placeholder="••••••••"
            style={{ borderRadius: 'var(--radius-md)', padding: '0.75rem' }}
          />
        </div>

        <button 
          type="submit" 
          className="btn w-100 text-white fw-bold py-2" 
          disabled={loading}
          style={{ 
            background: 'var(--verde-bosque)', 
            borderRadius: 'var(--radius-md)',
            transition: 'var(--transition)'
          }}
        >
          {loading ? 'Entrando...' : 'Iniciar sesión'}
        </button>
      </form>

      <p className="text-center mt-4 mb-0" style={{ fontSize: '0.9rem', color: 'var(--piedra)' }}>
        ¿No tienes cuenta? <Link to="/register" style={{ color: 'var(--verde-medio)', fontWeight: '600', textDecoration: 'underline' }}>Regístrate</Link>
      </p>
    </div>
  );
}