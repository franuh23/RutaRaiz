import React from 'react';
import EtapaCard from './EtapaCard';

export default function ResultadoPlanificador({
  etapas,
  mensajeGuardado,
  guardando,
  onGuardar,
  onVerPlanificaciones
}) {
  return (
    <div className="card shadow-sm border-0 p-4 mb-5" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h2 className="h4 mb-3" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>Resultado</h2>
      
      <div className="alert alert-success py-2 fw-bold mb-4 text-center" style={{ background: 'var(--crema-oscura)', color: 'var(--verde-bosque)', border: '0' }}>
        {etapas.total_km} km en {etapas.dias_totales} días
      </div>

      <div className="mb-4">
        {etapas.etapas?.map((etapa) => (
          <EtapaCard key={etapa.dia} etapa={etapa} />
        ))}
      </div>

      {mensajeGuardado ? (
        <div className="p-3 border rounded text-center" style={{ backgroundColor: '#e8f5e9', color: '#2e7d32', fontWeight: '600' }}>
          <p className="mb-2">{mensajeGuardado}</p>
          <button className="btn btn-success btn-sm fw-bold px-4" onClick={onVerPlanificaciones}>
            Ver mis planificaciones
          </button>
        </div>
      ) : (
        <button 
          className="btn text-white fw-bold w-100 py-2" 
          onClick={onGuardar}
          disabled={guardando}
          style={{ background: 'var(--tierra)', borderRadius: 'var(--radius-md)' }}
        >
          {guardando ? 'Guardando...' : '💾 Guardar planificación'}
        </button>
      )}
    </div>
  );
}