import React, { useState } from 'react';
// Gestiona los comentarios de un alojamiento.

export default function AlojamientoComentarios({ 
  comentarios = [], 
  onEnviarComentario, 
  onBorrarComentario,
  currentUser
}) {
  const [nuevoComentario, setNuevoComentario] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!nuevoComentario.trim()) return;
    onEnviarComentario(nuevoComentario);
    setNuevoComentario('');
  };

  return (
    <div className="card border-0 shadow-sm p-4 mb-4 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h2 className="h4 fw-bold text-dark border-top pt-3 mt-2 mb-3" style={{ fontFamily: 'var(--font-display)' }}>
        Comentarios
      </h2>

      {comentarios.length === 0 ? (
        <p className="text-secondary mb-4 fs-5">Sin comentarios aún.</p>
      ) : (
        <div className="d-flex flex-column gap-3 mb-4">
          {comentarios.map((c) => {
            const esAutor = currentUser && Number(c.usuario_id) === Number(currentUser.id);
            const esAdmin = currentUser?.rol === 'admin';
            const puedeBorrar = esAutor || esAdmin;

            return (
              <div key={c.id} className="p-3 bg-light rounded position-relative" style={{ borderRadius: 'var(--radius-md)' }}>
                <div className="d-flex justify-content-between mb-1 small text-muted">
                  <span className="fw-bold text-dark">👤 {c.usuario?.nick || 'Peregrino'}</span>
                  <div className="d-flex align-items-center gap-2">
                    <span>{c.created_at ? new Date(c.created_at).toLocaleDateString() : ''}</span>
                    
                    {puedeBorrar && (
                      <button
                        className="btn btn-sm text-danger p-0 border-0 bg-transparent ms-2"
                        title="Eliminar comentario"
                        onClick={() => {
                          if (window.confirm('¿Seguro que quieres retirar este comentario?')) {
                            onBorrarComentario(c.id);
                          }
                        }}
                        style={{ cursor: 'pointer', fontSize: '0.9rem' }}
                      >
                        🗑️
                      </button>
                    )}
                  </div>
                </div>
                <p className="m-0 text-secondary pe-4" style={{ whiteSpace: 'pre-line' }}>{c.texto}</p>
              </div>
            );
          })}
        </div>
      )}

      <h3 className="h5 fw-bold text-dark mb-3" style={{ fontFamily: 'var(--font-display)' }}>
        Deja tu comentario
      </h3>
      
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <textarea
            className="form-control p-3 bg-white border"
            rows="4"
            placeholder="Escribe tu comentario..."
            value={nuevoComentario}
            onChange={(e) => setNuevoComentario(e.target.value)}
            style={{ borderRadius: 'var(--radius-sm)', resize: 'none' }}
          ></textarea>
        </div>
        <button
          type="submit"
          className="btn btn-light border px-3 py-1.5 fw-semibold text-dark"
          style={{ borderRadius: 'var(--radius-sm)', backgroundColor: '#efefef' }}
        >
          Enviar comentario
        </button>
      </form>
    </div>
  );
}