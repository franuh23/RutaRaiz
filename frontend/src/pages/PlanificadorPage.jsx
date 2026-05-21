import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import FormularioPlanificador from '../components/planificacion/FormularioPlanificador';
import ResultadoPlanificador from '../components/planificacion/ResultadoPlanificador';

export default function PlanificadorPage() {
  const { token, isAuthenticated } = useAuth();
  const navigate = useNavigate();
  const [rutas, setRutas] = useState([]);
  const [selectedRuta, setSelectedRuta] = useState('');
  const [inicioId, setInicioId] = useState('');
  const [finId, setFinId] = useState('');
  const [kmDia, setKmDia] = useState(20);
  const [fechaInicio, setFechaInicio] = useState('');
  const [localizaciones, setLocalizaciones] = useState([]);
  const [etapas, setEtapas] = useState(null);
  const [loading, setLoading] = useState(false);
  const [guardando, setGuardando] = useState(false);
  const [error, setError] = useState('');
  const [mensajeGuardado, setMensajeGuardado] = useState('');

  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => setRutas(data.data))
      .catch(err => console.error(err));
  }, []);

  useEffect(() => {
    if (selectedRuta) {
      fetch(`/api/rutas/${selectedRuta}`)
        .then(res => res.json())
        .then(data => {
          setLocalizaciones(data.data.localizaciones || []);
          setInicioId('');
          setFinId('');
        })
        .catch(err => console.error(err));
    }
  }, [selectedRuta]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setEtapas(null);
    setMensajeGuardado('');
    try {
      const url = `/api/rutas/planificar?ruta_id=${selectedRuta}&localizacion_inicio_id=${inicioId}&km_dia=${kmDia}${finId ? `&localizacion_fin_id=${finId}` : ''}`;
      const response = await fetch(url);
      const data = await response.json();
      if (response.ok) {
        setEtapas(data);
      } else {
        setError(data.error || 'Error al planificar');
      }
    } catch (err) {
      setError('Error de conexión');
    } finally {
      setLoading(false);
    }
  };

  const handleGuardar = async () => {
    if (!isAuthenticated) {
      navigate('/login');
      return;
    }
    if (!fechaInicio) {
      setError('Indica una fecha de inicio para guardar la planificación');
      return;
    }
    setGuardando(true);
    setError('');
    try {
      const response = await fetch('/api/planificaciones', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          ruta_id: selectedRuta,
          localizacion_inicio_id: inicioId,
          localizacion_fin_id: finId || null,
          fecha_inicio: fechaInicio,
          km_dia: kmDia
        })
      });
      const data = await response.json();
      if (response.ok) {
        setMensajeGuardado('¡Planificación guardada correctamente!');
      } else {
        setError(data.message || 'Error al guardar');
      }
    } catch (err) {
      setError('Error de conexión');
    } finally {
      setGuardando(false);
    }
  };

  return (
    <Container>
      <h1 className="h2 my-4" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
        Planificador de rutas
      </h1>

      <FormularioPlanificador
        rutas={rutas}
        selectedRuta={selectedRuta}
        setSelectedRuta={setSelectedRuta}
        localizaciones={localizaciones}
        inicioId={inicioId}
        setInicioId={setInicioId}
        finId={finId}
        setFinId={setFinId}
        kmDia={kmDia}
        setKmDia={setKmDia}
        fechaInicio={fechaInicio}
        setFechaInicio={setFechaInicio}
        onSubmit={handleSubmit}
        loading={loading}
      />

      {error && (
        <div className="alert alert-danger py-2 mb-4" role="alert" style={{ borderRadius: 'var(--radius-md)' }}>
          {error}
        </div>
      )}

      {etapas && (
        <ResultadoPlanificador
          etapas={etapas}
          mensajeGuardado={mensajeGuardado}
          guardando={guardando}
          onGuardar={handleGuardar}
          onVerPlanificaciones={() => navigate('/mis-planificaciones')}
        />
      )}
    </Container>
  );
}