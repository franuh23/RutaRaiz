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

  // --- PERSISTENCIA MODO 3.1 PRO ---
  const [tipoPlanificacion, setTipoPlanificacion] = useState(() => localStorage.getItem('rr_tipoPlanificacion') || 'destino_ritmo');
  const [diasDisponibles, setDiasDisponibles] = useState(() => Number(localStorage.getItem('rr_diasDisponibles')) || 10);

  const [selectedRuta, setSelectedRuta] = useState(() => localStorage.getItem('rr_selectedRuta') || '');
  const [inicioId, setInicioId] = useState(() => localStorage.getItem('rr_inicioId') || '');
  const [finId, setFinId] = useState(() => localStorage.getItem('rr_finId') || '');
  const [kmDia, setKmDia] = useState(() => Number(localStorage.getItem('rr_kmDia')) || 20);
  const [fechaInicio, setFechaInicio] = useState(() => localStorage.getItem('rr_fechaInicio') || '');
  const [etapas, setEtapas] = useState(() => {
    const saved = localStorage.getItem('rr_etapas');
    return saved && saved !== "undefined" ? JSON.parse(saved) : null;
  });

  // Sincronizar todos los cambios en localStorage
  useEffect(() => {
    localStorage.setItem('rr_tipoPlanificacion', tipoPlanificacion);
    localStorage.setItem('rr_diasDisponibles', diasDisponibles.toString());
    localStorage.setItem('rr_selectedRuta', selectedRuta);
    localStorage.setItem('rr_inicioId', inicioId);
    localStorage.setItem('rr_finId', finId);
    localStorage.setItem('rr_kmDia', kmDia.toString());
    localStorage.setItem('rr_fechaInicio', fechaInicio);
    if (etapas) {
      localStorage.setItem('rr_etapas', JSON.stringify(etapas));
    } else {
      localStorage.removeItem('rr_etapas');
    }
  }, [tipoPlanificacion, diasDisponibles, selectedRuta, inicioId, finId, kmDia, fechaInicio, etapas]);

  // Cargar lista de rutas inicial
  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => {
        const rutasData = data.data || [];
        setRutas(rutasData);

        if (selectedRuta) {
          const rutaMatch = rutasData.find(r => String(r.id) === String(selectedRuta));
          if (rutaMatch) {
            setLocalizaciones(rutaMatch.localizaciones || []);
          }
        }
      })
      .catch(err => console.error("Error cargando rutas base:", err));
  }, []);

  const handleSelectedRutaChange = (nuevaRutaId) => {
    setSelectedRuta(nuevaRutaId);
    if (nuevaRutaId) {
      const rutaMatch = rutas.find(r => String(r.id) === String(nuevaRutaId));
      setLocalizaciones(rutaMatch ? (rutaMatch.localizaciones || []) : []);
      if (localStorage.getItem('rr_selectedRuta') !== nuevaRutaId) {
        setInicioId('');
        setFinId('');
        setEtapas(null);
      }
    } else {
      setLocalizaciones([]);
      setInicioId('');
      setFinId('');
      setEtapas(null);
    }
  };

  // 🌀 SIMULACIÓN DE PREVISIÓN DE ETAPAS (Adaptado a las variantes de cálculo)
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setEtapas(null);
    setMensajeGuardado('');
    try {
      // Configuramos las variables URL en base al tipo seleccionado
      let queryParams = `ruta_id=${selectedRuta}&localizacion_inicio_id=${inicioId}&tipo_planificacion=${tipoPlanificacion}`;
      
      if (tipoPlanificacion !== 'destino_dias') queryParams += `&km_dia=${kmDia}`;
      if (tipoPlanificacion !== 'dias_ritmo' && finId) queryParams += `&localizacion_fin_id=${finId}`;
      if (tipoPlanificacion !== 'destino_ritmo') queryParams += `&dias_disponibles=${diasDisponibles}`;

      // Reutiliza la lógica de previsualización pasando los nuevos flags
      const response = await fetch(`/api/rutas/planificar?${queryParams}`);
      const data = await response.json();
      if (response.ok) {
        setEtapas(data);
      } else {
        setError(data.error || 'Error al calcular el itinerario solicitado.');
      }
    } catch (err) {
      setError('Error de conexión con los servidores de RutaRaíz.');
    } finally {
      setLoading(false);
    }
  };

  // 💾 PERSISTENCIA FINAL DE LA PLANIFICACIÓN EN NEON
  const handleGuardar = async () => {
    if (!isAuthenticated) {
      navigate('/login');
      return;
    }

    if (!fechaInicio) {
      setError('Indica una fecha de inicio válida para poder guardar la planificación.');
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    setGuardando(true);
    setError('');

    // Ajustamos dinámicamente el payload JSON según las reglas que espera Laravel
    const payload = {
      ruta_id: selectedRuta,
      localizacion_inicio_id: inicioId,
      fecha_inicio: fechaInicio,
      tipo_planificacion: tipoPlanificacion,
      dias_disponibles: tipoPlanificacion !== 'destino_ritmo' ? diasDisponibles : null,
      km_dia: tipoPlanificacion !== 'destino_dias' ? kmDia : null,
      localizacion_fin_id: tipoPlanificacion !== 'dias_ritmo' ? (finId || null) : null
    };

    try {
      const response = await fetch('/api/planificaciones', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json();

      if (response.ok) {
        handleLimpiarCache();
        navigate('/mis-planificaciones');
      } else {
        setError(data.message || data.error || 'Error al registrar la planificación.');
      }
    } catch (err) {
      console.error(err);
      setError('Error de red al intentar consolidar tu ruta.');
    } finally {
      setGuardando(false);
    }
  };

  const handleLimpiarCache = () => {
    setTipoPlanificacion('destino_ritmo');
    setDiasDisponibles(10);
    setSelectedRuta('');
    setInicioId('');
    setFinId('');
    setKmDia(20);
    setFechaInicio('');
    setEtapas(null);
    setError('');
    setMensajeGuardado('');

    localStorage.removeItem('rr_tipoPlanificacion');
    localStorage.removeItem('rr_diasDisponibles');
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
        {isAuthenticated && (selectedRuta || etapas) && (
          <button className="btn btn-sm btn-outline-secondary px-3" onClick={handleLimpiarCache} style={{ borderRadius: 'var(--radius-md)' }}>
            Limpiar Formulario
          </button>
        )}
      </div>

      {isAuthenticated ? (
        <FormularioPlanificador
          rutas={rutas}
          selectedRuta={selectedRuta}
          setSelectedRuta={handleSelectedRutaChange}
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
          // Inyección de estados Pro
          tipoPlanificacion={tipoPlanificacion}
          setTipoPlanificacion={setTipoPlanificacion}
          diasDisponibles={diasDisponibles}
          setDiasDisponibles={setDiasDisponibles}
        />
      ) : (
        <div className="card shadow-sm border-0 p-5 mb-4 bg-white text-center" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="py-3 max-w-md mx-auto">
            <div className="d-flex align-items-center justify-content-center mx-auto mb-3" style={{ width: '60px', height: '60px', borderRadius: '50%', backgroundColor: 'rgba(74, 114, 85, 0.1)', color: 'var(--verde-bosque)', fontSize: '24px' }}>
              <i className="fa-solid fa-lock"></i>
            </div>
            <h3 className="h4 fw-bold" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)' }}>Planifica tu propio Camino</h3>
            <p className="text-muted small mb-4">Inicia sesión para poder calcular tus etapas personalizadas según tu tiempo y ritmo de marcha.</p>
            <div className="d-flex gap-3 justify-content-center">
              <button className="btn text-white px-4 py-2 fw-semibold" onClick={() => navigate('/login')} style={{ backgroundColor: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)', fontSize: '14px', border: 'none' }}>Iniciar Sesión</button>
              <button className="btn btn-outline-secondary px-4 py-2 fw-semibold" onClick={() => navigate('/register')} style={{ borderRadius: 'var(--radius-md)', fontSize: '14px' }}>Crear una cuenta</button>
            </div>
          </div>
        </div>
      )}

      {error && (
        <div className="alert alert-danger py-2 mb-4" role="alert" style={{ borderRadius: 'var(--radius-md)' }}>
          {error}
        </div>
      )}

      {/* Minitutorial de bienvenida */}
      {!etapas && !loading && (
        <div className="card shadow-sm border-0 p-4 mb-5 bg-white text-center" style={{ borderRadius: 'var(--radius-lg)' }}>
          <div className="py-3">
            <span className="fs-1">🗺️</span>
            <h3 className="h4 fw-bold mt-2" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)' }}>¿Cómo funciona tu Planificador de RutaRaíz?</h3>
            <p className="text-muted small max-w-md mx-auto mb-4">Configura tu viaje eligiendo el destino clásico o delimitando el itinerario según los días de vacaciones que tengas libres en tu calendario.</p>
          </div>
          <div className="row g-4 mt-1">
            <div className="col-12 col-md-4">
              <div className="p-4 h-100" style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}>
                <div className="fs-3 mb-2">🥾</div>
                <h4 className="h6 fw-bold text-dark mb-2">1. Elige la Estrategia</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem' }}>Elige si prefieres calcular por ritmo diario o dejar que la app configure tu velocidad en base a tus jornadas disponibles.</p>
              </div>
            </div>
            <div className="col-12 col-md-4">
              <div className="p-4 h-100" style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}>
                <div className="fs-3 mb-2">⚡</div>
                <h4 className="h6 fw-bold text-dark mb-2">2. Visualiza Paradas</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem' }}>Nuestro algoritmo segmentará las distancias geográficas reales y te indicará los pueblos exactos donde harás noche.</p>
              </div>
            </div>
            <div className="col-12 col-md-4">
              <div className="p-4 h-100" style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}>
                <div className="fs-3 mb-2">💾</div>
                <h4 className="h6 fw-bold text-dark mb-2">3. Guarda en Perfil</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem' }}>Consolida el itinerario en tu mochila para activar su seguimiento interactivo y consultar la previsión del clima en ruta.</p>
              </div>
            </div>
          </div>
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