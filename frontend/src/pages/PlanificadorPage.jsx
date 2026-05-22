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
    const saved = localStorage.getItem('rr_etapas');
    return saved && saved !== "undefined" ? JSON.parse(saved) : null;
  });

  // --- PERSISTENCIA: Sincronizar cambios en localStorage ---
  useEffect(() => {
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
  }, [selectedRuta, inicioId, finId, kmDia, fechaInicio, etapas]);

  // Cargar lista de rutas inicial (Ya vienen con sus localizaciones acopladas de Laravel)
  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => {
        const rutasData = data.data || [];
        setRutas(rutasData);

        // 💡 SALVAVIDAS DE PERSISTENCIA: Si al recargar la página ya había una ruta seleccionada
        // en el LocalStorage, buscamos sus localizaciones inmediatamente en memoria.
        if (selectedRuta) {
          const rutaMatch = rutasData.find(r => String(r.id) === String(selectedRuta));
          if (rutaMatch) {
            setLocalizaciones(rutaMatch.localizaciones || []);
          }
        }
      })
      .catch(err => console.error("Error cargando rutas base:", err));
  }, []);

  // 🔥 EL CAMBIO CLAVE: Sincronizar localizaciones al vuelo sin peticiones HTTP
  const handleSelectedRutaChange = (nuevaRutaId) => {
    setSelectedRuta(nuevaRutaId);

    if (nuevaRutaId) {
      // Buscamos la ruta elegida directamente en el array que ya descargamos en memoria
      const rutaMatch = rutas.find(r => String(r.id) === String(nuevaRutaId));

      if (rutaMatch) {
        setLocalizaciones(rutaMatch.localizaciones || []);
      } else {
        setLocalizaciones([]);
      }

      // Si la ruta cambia de verdad respecto a la guardada, reseteamos selectores dependientes
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

    // 1. CONTROL ANTES DE NADA: Bloqueo inmediato en el primer milisegundo si falta la fecha
    if (!fechaInicio || fechaInicio.trim() === '') {
      setError('Indica una fecha de inicio válida para poder guardar la planificación en tu perfil.');
      // Hacemos scroll hacia arriba para que el usuario vea el cartel rojo de Bootstrap perfectamente
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    setGuardando(true);
    setError('');
    setMensajeGuardado('');

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
        // Limpiamos los datos del formulario para dejar la mesa de trabajo limpia
        handleLimpiarCache();
      } else {
        // Si Laravel devuelve fallos de validación (422), los pintamos en el cuadro rojo
        setError(data.message || data.error || 'Error al guardar la ruta.');
      }
    } catch (err) {
      console.error("Error de red al guardar:", err);
      setError('Error de conexión: El servidor no ha respondido a la petición de guardado.');
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

    // 💡 SELECCIÓN SELECTIVA: Borramos solo lo del planificador,
    // NUNCA uses localStorage.clear() a secas porque te llevarías por delante el 'token' de sesión.
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
        setSelectedRuta={handleSelectedRutaChange} // 👈 Pasamos nuestra nueva función optimizada en memoria
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