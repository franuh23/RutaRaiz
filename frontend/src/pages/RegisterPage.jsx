import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import styles from './AuthPage.module.css';

export default function RegisterPage() {
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
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(form)
            });
            const data = await response.json();

            if (!response.ok) {
                const msgs = data.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : data.message;
                throw new Error(msgs);
            }

            // Guardamos el token y logueamos
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
        <Container>
            <div className={styles.wrapper}>
                <div className={styles.card}>
                    <h1 className={styles.titulo}>Crear cuenta</h1>
                    <form onSubmit={handleSubmit} className={styles.form}>
                        {[
                            { name: 'nick', label: 'Nombre de usuario', type: 'text', placeholder: 'mi_nick' },
                            { name: 'nombre', label: 'Nombre', type: 'text', placeholder: 'Juan' },
                            { name: 'apellidos', label: 'Apellidos', type: 'text', placeholder: 'García López' },
                            { name: 'email', label: 'Email', type: 'email', placeholder: 'tu@email.com' },
                            { name: 'password', label: 'Contraseña', type: 'password', placeholder: '••••••••' },
                            { name: 'password_confirmation', label: 'Repetir contraseña', type: 'password', placeholder: '••••••••' },
                        ].map(({ name, label, type, placeholder }) => (
                            <div key={name} className={styles.formGroup}>
                                <label>{label}</label>
                                <input
                                    type={type}
                                    name={name}
                                    value={form[name]}
                                    onChange={handleChange}
                                    required
                                    placeholder={placeholder}
                                />
                            </div>
                        ))}
                        {error && <div className={styles.error}>{error}</div>}
                        <button type="submit" className={styles.button} disabled={loading}>
                            {loading ? 'Creando cuenta...' : 'Registrarse'}
                        </button>
                    </form>
                    <p className={styles.link}>
                        ¿Ya tienes cuenta? <Link to="/login">Inicia sesión</Link>
                    </p>
                </div>
            </div>
        </Container>
    );
}