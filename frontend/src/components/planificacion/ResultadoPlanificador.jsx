import React from 'react';
import EtapaCard from './EtapaCard';
import Button from '../ui/Button';
import Badge from '../ui/Badge';

export default function ResultadoPlanificador({
  etapas,
  mensajeGuardado,
  guardando,
  onGuardar,
  onVerPlanificaciones
}) {
  const listadoEtapas = etapas?.etapas || [];

  return (
    <div className="card shadow-sm border-0 p-4 mb-5 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
      <h2 className="h4 mb-3" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)', fontWeight: '700' }}>
        Planificación Generada
      </h2>
      
      <div className="alert py-3 fw-bold mb-4 text-center d-flex align-items-center justify-content-center gap-3 border-0" 
           style={{ background: 'var(--crema-oscura)', color: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}>
        <span>📏 Total recorrido: <strong>{etapas?.total_km || 0} km</strong></span>
        <div style={{ width: '1px', height: '20px', background: 'var(--verde-medio)', opacity: '0.3' }} />
        <span>🗓️ Tiempo estimado: <strong>{etapas?.dias_totales || 0} {etapas?.dias_totales === 1 ? 'día' : 'días'}</strong></span>
      </div>

      <div className="mb-4 d-flex flex-column gap-1">
        {listadoEtapas.map((etapa) => (
          <EtapaCard key={etapa.dia} etapa={etapa} />
        ))}
      </div>

      <Button 
        variant="primary" 
        className="w-100 py-2 text-white fw-bold" 
        onClick={onGuardar}
        disabled={guardando}
      >
        {guardando ? 'Archivando en tu cuenta...' : '💾 Guardar planificación en perfil'}
      </Button>
    </div>
  );
}