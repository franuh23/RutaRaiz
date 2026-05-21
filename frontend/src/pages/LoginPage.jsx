import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import styles from './AuthPage.module.css';

export default function LoginPage() {
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
        <Container>
            <div className={styles.wrapper}>
                <div className={styles.card}>
                    <h1 className={styles.titulo}>Iniciar sesión</h1>
                    <form onSubmit={handleSubmit} className={styles.form}>
                        <div className={styles.formGroup}>
                            <label>Email</label>
                            <input
                                type="email"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                                placeholder="tu@email.com"
                            />
                        </div>
                        <div className={styles.formGroup}>
                            <label>Contraseña</label>
                            <input
                                type="password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                                placeholder="••••••••"
                            />
                        </div>
                        {error && <div className={styles.error}>{error}</div>}
                        <button type="submit" className={styles.button} disabled={loading}>
                            {loading ? 'Entrando...' : 'Iniciar sesión'}
                        </button>
                    </form>
                    <p className={styles.link}>
                        ¿No tienes cuenta? <Link to="/register">Regístrate</Link>
                    </p>
                </div>
            </div>
        </Container>
    );
}