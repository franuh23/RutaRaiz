import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import PerfilInfo from '../components/profile/PerfilInfo';
import PerfilForm from '../components/profile/PerfilForm';
import { apiFetch } from '../services/api';

export default function PerfilPage() {
  // Extraemos 'setUser' de tu contexto para poder actualizar la memoria de React al instante
  // Si tu contexto usa otro nombre (como 'updateUser'), cambia 'setUser' por ese nombre
  const { user, token, setUser } = useAuth(); 
  const [feedback, setFeedback] = useState({ status: '', msg: '' });

  const handleUpdate = async (formDataObjects) => {
    setFeedback({ status: '', msg: '' });

    if (formDataObjects.password && formDataObjects.password !== formDataObjects.password_confirmation) {
      setFeedback({ status: 'danger', msg: 'Las contraseñas nuevas no coinciden' });
      return;
    }

    const dataToSend = new FormData();
    dataToSend.append('nombre', formDataObjects.nombre);
    dataToSend.append('apellidos', formDataObjects.apellidos);
    dataToSend.append('nick', formDataObjects.nick);
    dataToSend.append('email', formDataObjects.email);
    
    if (formDataObjects.password) {
      dataToSend.append('password', formDataObjects.password);
      dataToSend.append('password_confirmation', formDataObjects.password_confirmation);
    }
    
    if (formDataObjects.avatar) {
      dataToSend.append('avatar', formDataObjects.avatar);
    }

    try {
      const res = await apiFetch('/api/usuario/update', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: dataToSend
      });

      const data = await res.json();

      if (res.ok) {
        setFeedback({ status: 'success', msg: '¡Perfil y avatar actualizados correctamente!' });
        
        // 🔥 LA MAGIA ESTÁ AQUÍ:
        // Si tu AuthContext expone 'setUser', actualizamos el estado global con los datos frescos que devuelve Laravel
        if (setUser && data.user) {
          setUser(data.user);
          
          // Opcional: Si guardas el usuario en el localStorage dentro del AuthContext para que no se pierda al recargar,
          // lo actualizamos también aquí para que al salir y volver persistan los datos nuevos
          localStorage.setItem('user', JSON.stringify(data.user));
        }
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