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
  const [loading, setLoading] = useState(false);
  const [guardando, setGuardando] = useState(false);
  const [error, setError] = useState('');
  const [mensajeGuardado, setMensajeGuardado] = useState('');
  const [localizaciones, setLocalizaciones] = useState([]);

  // --- PERSISTENCIA: Inicializar estados desde localStorage ---
  const [selectedRuta, setSelectedRuta] = useState(() => localStorage.getItem('rr_selectedRuta') || '');
  const [inicioId, setInicioId] = useState(() => localStorage.getItem('rr_inicioId') || '');
  const [finId, setFinId] = useState(() => localStorage.getItem('rr_finId') || '');
  const [kmDia, setKmDia] = useState(() => Number(localStorage.getItem('rr_kmDia')) || 20);
  const [fechaInicio, setFechaInicio] = useState(() => localStorage.getItem('rr_fechaInicio') || '');
  const [etapas, setEtapas] = useState(() => {
    const savedEtapas = localStorage.getItem('rr_etapas');
    return savedEtapas ? JSON.parse(savedEtapas) : null;
  });

  // --- PERSISTENCIA: Sincronizar cambios en localStorage ---
  useEffect(() => { localStorage.setItem('rr_selectedRuta', selectedRuta); }, [selectedRuta]);
  useEffect(() => { localStorage.setItem('rr_inicioId', inicioId); }, [inicioId]);
  useEffect(() => { localStorage.setItem('rr_finId', finId); }, [finId]);
  useEffect(() => { localStorage.setItem('rr_kmDia', kmDia); }, [kmDia]);
  useEffect(() => { localStorage.setItem('rr_fechaInicio', fechaInicio); }, [fechaInicio]);
  useEffect(() => {
    if (etapas) {
      localStorage.setItem('rr_etapas', JSON.stringify(etapas));
    } else {
      localStorage.removeItem('rr_etapas');
    }
  }, [etapas]);

  // Cargar lista de rutas inicial
  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => setRutas(data.data || []))
      .catch(err => console.error(err));
  }, []);

  // Cargar localizaciones de la ruta seleccionada (respeta si ya había ids guardados)
  useEffect(() => {
    if (selectedRuta) {
      fetch(`/api/rutas/${selectedRuta}`)
        .then(res => res.json())
        .then(data => {
          setLocalizaciones(data.data.localizaciones || []);
          // Solo limpiamos los IDs si la ruta ha cambiado de verdad y no coincide con lo guardado
          if (localStorage.getItem('rr_selectedRuta') !== selectedRuta) {
            setInicioId('');
            setFinId('');
            setEtapas(null);
          }
        })
        .catch(err => console.error(err));
    } else {
      setLocalizaciones([]);
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
        // Limpiamos la caché del planificador al guardar con éxito para poder hacer otra nueva
        handleLimpiarCache();
      } else {
        setError(data.message || 'Error al guardar');
      }
    } catch (err) {
      setError('Error de conexión');
    } finally {
      setGuardando(false);
    }
  };

  const handleLimpiarCache = () => {
    setSelectedRuta('');
    setInicioId('');
    setFinId('');
    setKmDia(20);
    setFechaInicio('');
    setEtapas(null);
    setError('');
    setMensajeGuardado('');
    localStorage.removeItem('rr_selectedRuta');
    localStorage.removeItem('rr_inicioId');
    localStorage.removeItem('rr_finId');
    localStorage.removeItem('rr_kmDia');
    localStorage.removeItem('rr_fechaInicio');
    localStorage.removeItem('rr_etapas');
  };

  return (
    <Container>
      <div className="d-flex justify-content-between align-items-center flex-wrap gap-2 my-4">
        <h1 className="h2 m-0" style={{ color: 'var(--verde-bosque)', fontWeight: '700' }}>
          Planificador de rutas
        </h1>
        {(selectedRuta || etapas) && (
          <button className="btn btn-sm btn-outline-secondary px-3" onClick={handleLimpiarCache} style={{ borderRadius: 'var(--radius-md)' }}>
            🧹 Limpiar Formulario
          </button>
        )}
      </div>

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