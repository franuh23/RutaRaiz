import React from 'react';

export default function LineaProgreso({ etapas = [], onToggle }) {
  if (etapas.length === 0) return null;

  return (
    <div className="position-relative ps-2 pt-2">
      {etapas.map((etapa, index) => {
        const { id, dia, inicio, fin, distancia, completada } = etapa;

        // 🚀 REGLA DE COHERENCIA EN CASCADA
        // Se puede pulsar si es el primer día O SI el día anterior ya está completado
        const diaAnteriorHecho = index === 0 ? true : etapas[index - 1].completada;
        
        // Regla para poder desmarcar: solo se puede desmarcar si el día siguiente NO está hecho
        const diaSiguienteHecho = index === etapas.length - 1 ? false : etapas[index + 1].completada;

        // Combinamos ambas: el botón está habilitado si se puede empezar o si se puede desmarcar
        const canToggle = (!completada && diaAnteriorHecho) || (completada && !diaSiguienteHecho);

        return (
          <div key={id || index} className="d-flex mb-4 position-relative align-items-start gap-3" style={{ opacity: canToggle ? 1 : 0.5 }}>
            
            {/* LÍNEA CONECTOR VERTICAL */}
            {index !== etapas.length - 1 && (
              <div 
                className="position-absolute" 
                style={{
                  left: '19px',
                  top: '38px',
                  bottom: '-28px',
                  width: '4px',
                  backgroundColor: completada ? 'var(--verde-medio)' : '#edf2f7',
                  transition: 'background-color 0.2s ease',
                  zIndex: 1
                }}
              />
            )}

            {/* 🔘 CÍRCULO / CHECKBOX INTERACTIVO (Con seguro disabled) */}
            <button
              onClick={() => canToggle && onToggle(id)}
              disabled={!canToggle} // 🚀 Forzamos el bloqueo nativo de HTML
              className="d-flex align-items-center justify-content-center text-white border-0 p-0 shadow-sm"
              style={{
                width: '42px',
                height: '42px',
                borderRadius: '50%',
                backgroundColor: completada ? 'var(--verde-medio)' : '#fff',
                border: completada ? 'none' : '3px solid #cbd5e1',
                color: completada ? '#fff' : '#64748b',
                cursor: canToggle ? 'pointer' : 'not-allowed',
                transition: 'all 0.2s ease',
                zIndex: 2,
                flexShrink: 0
              }}
              title={!canToggle ? 'Debes seguir el orden cronológico del camino' : completada ? 'Marcar como pendiente' : 'Marcar como completada'}
            >
              {completada ? (
                <i className="fa-solid fa-check fs-5"></i>
              ) : (
                <span className="fw-bold small">{dia}</span>
              )}
            </button>

            {/* DESCRIPCIÓN GEOGRÁFICA */}
            <div 
              className="p-3 border flex-grow-1 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2"
              style={{ 
                borderRadius: 'var(--radius-md)',
                backgroundColor: completada ? '#f8fafc' : '#fff',
                borderColor: completada ? '#e2e8f0' : '#edf2f7',
                opacity: completada ? 0.8 : 1
              }}
            >
              <div>
                <div className="d-flex align-items-center gap-2 flex-wrap">
                  <span className={`fw-bold ${completada ? 'text-muted text-decoration-line-through' : 'text-dark'}`}>
                    {inicio}
                  </span>
                  <span className="text-muted small opacity-50">➔</span>
                  <span className="fw-bold" style={{ color: completada ? '#64748b' : 'var(--verde-bosque)' }}>
                    {fin}
                  </span>
                </div>
                <small className="text-muted d-block mt-1 fw-medium">
                  {!canToggle && !completada ? '🔒 Tramo bloqueado temporalmente' : 'Jornada de marcha recomendada'}
                </small>
              </div>

              <span className={`badge px-3 py-2 fw-bold ${completada ? 'bg-secondary text-white' : 'bg-light text-dark border'}`} style={{ borderRadius: 'var(--radius-md)', fontSize: '12px' }}>
                {distancia} km
              </span>
            </div>

          </div>
        );
      })}
    </div>
  );
}