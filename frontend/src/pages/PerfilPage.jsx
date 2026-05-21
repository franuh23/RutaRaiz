import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import PerfilInfo from '../components/profile/PerfilInfo';
import PerfilForm from '../components/profile/PerfilForm';

export default function PerfilPage() {
  const { user, token, login } = useAuth(); // Usamos login para refrescar estado si es necesario
  const [feedback, setFeedback] = useState({ status: '', msg: '' });

  const handleUpdate = async (formData) => {
    setFeedback({ status: '', msg: '' });

    if (formData.password && formData.password !== formData.password_confirmation) {
      setFeedback({ status: 'danger', msg: 'Las contraseñas nuevas no coinciden' });
      return;
    }

    try {
      // Endpoint simulado o mapeado a tu controlador de usuarios/perfil
      const res = await fetch('/api/usuario/update', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(formData)
      });

      const data = await res.json();

      if (res.ok) {
        setFeedback({ status: 'success', msg: '¡Perfil actualizado correctamente!' });
        // Aquí refrescarías el usuario en el contexto si tu backend retorna el objeto actualizado
      } else {
        setFeedback({ status: 'danger', msg: data.message || 'Error al actualizar el perfil' });
      }
    } catch (err) {
      setFeedback({ status: 'danger', msg: 'Error de conexión con el servidor' });
    }
  };

  return (
    <Container>
      <h1 className="h2 my-4" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
        ⚙️ Mi Perfil de Peregrino
      </h1>

      {feedback.msg && (
        <div className={`alert alert-${feedback.status} py-2 mb-4`} role="alert" style={{ borderRadius: 'var(--radius-md)' }}>
          {feedback.msg}
        </div>
      )}

      <div className="row g-4 mb-5">
        <div className="col-12 col-lg-4">
          <PerfilInfo user={user} />
        </div>
        <div className="col-12 col-lg-8">
          <PerfilForm user={user} onUpdate={handleUpdate} />
        </div>
      </div>
    </Container>
  );
}