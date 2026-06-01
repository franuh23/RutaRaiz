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

    if (!fechaInicio || fechaInicio.trim() === '') {
      setError('Indica una fecha de inicio válida para poder guardar la planificación en tu perfil.');
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
        // ✨ ¡LA CLAVE! Limpiamos el formulario en el acto para el próximo uso
        handleLimpiarCache();

        // 🚀 REDIRECCIÓN INMEDIATA: Mandamos al peregrino directo a su mochila
        navigate('/mis-planificaciones');
      } else {
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

  // ... (Todo tu código superior se queda exactamente igual)

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

      {/* 🔒 CONDICIONAL DE AUTENTICACIÓN */}
      {isAuthenticated ? (
        // 🟢 SI ESTÁ LOGUEADO: Mostramos el formulario de siempre
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
        />
      ) : (
        // 🔴 SI NO ESTÁ LOGUEADO: Mostramos la tarjeta de invitación al registro
        <div 
          className="card shadow-sm border-0 p-5 mb-4 bg-white text-center animate__animated animate__fadeIn" 
          style={{ borderRadius: 'var(--radius-lg)' }}
        >
          <div className="py-3 max-w-md mx-auto">
            <div 
              className="d-flex align-items-center justify-content-center mx-auto mb-3"
              style={{ 
                width: '60px', 
                height: '60px', 
                borderRadius: '50%', 
                backgroundColor: 'rgba(74, 114, 85, 0.1)', 
                color: 'var(--verde-bosque)',
                fontSize: '24px'
              }}
            >
              <i className="fa-solid fa-lock"></i>
            </div>
            <h3 className="h4 fw-bold" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)' }}>
              Planifica tu propio Camino
            </h3>
            <p className="text-muted small mb-4">
              Para calcular etapas personalizadas, ver alojamientos disponibles en cada parada y guardar las rutas en tu mochila virtual, necesitas formar parte de la comunidad de RutaRaíz.
            </p>
            <div className="d-flex gap-3 justify-content-center">
              <button 
                className="btn text-white px-4 py-2 fw-semibold"
                onClick={() => navigate('/login')}
                style={{ backgroundColor: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)', fontSize: '14px', border: 'none' }}
              >
                Iniciar Sesión
              </button>
              <button 
                className="btn btn-outline-secondary px-4 py-2 fw-semibold"
                onClick={() => navigate('/register')}
                style={{ borderRadius: 'var(--radius-md)', fontSize: '14px' }}
              >
                Crear una cuenta
              </button>
            </div>
          </div>
        </div>
      )}

      {error && (
        <div className="alert alert-danger py-2 mb-4" role="alert" style={{ borderRadius: 'var(--radius-md)' }}>
          {error}
        </div>
      )}

      {/* 🧭 MINITUTORIAL DE BIENVENIDA: Sigue saliendo debajo intacto si no hay etapas generadas */}
      {!etapas && !loading && (
        <div 
          className="card shadow-sm border-0 p-4 mb-5 bg-white text-center animate__animated animate__fadeIn" 
          style={{ borderRadius: 'var(--radius-lg)' }}
        >
          <div className="py-3">
            <span className="fs-1">🗺️</span>
            <h3 className="h4 fw-bold mt-2" style={{ color: 'var(--verde-bosque)', fontFamily: 'var(--font-display)' }}>
              ¿Cómo funciona tu Planificador de RutaRaíz?
            </h3>
            <p className="text-muted small max-w-md mx-auto mb-4">
              Configura tu ruta a medida en tres sencillos pasos antes de colgarte la mochila a la espalda.
            </p>
          </div>

          <div className="row g-4 mt-1">
            {/* Paso 1 */}
            <div className="col-12 col-md-4">
              <div 
                className="p-4 h-100" 
                style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}
              >
                <div className="fs-3 mb-2">🥾</div>
                <h4 className="h6 fw-bold text-dark mb-2">1. Elige tu Camino</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem', lineHeight: '1.4' }}>
                  Selecciona una ruta base y marca tu hito de salida. Si quieres hacer un tramo corto, puedes elegir también un punto de fin.
                </p>
              </div>
            </div>

            {/* Paso 2 */}
            <div className="col-12 col-md-4">
              <div 
                className="p-4 h-100" 
                style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}
              >
                <div className="fs-3 mb-2">⚡</div>
                <h4 className="h6 fw-bold text-dark mb-2">2. Define tu Ritmo</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem', lineHeight: '1.4' }}>
                  Ajusta los kilómetros diarios que quieres caminar según tu forma física. Calcularemos tus paradas de forma inteligente.
                </p>
              </div>
            </div>

            {/* Paso 3 */}
            <div className="col-12 col-md-4">
              <div 
                className="p-4 h-100" 
                style={{ backgroundColor: 'var(--crema-oscura)', borderRadius: 'var(--radius-md)' }}
              >
                <div className="fs-3 mb-2">💾</div>
                <h4 className="h6 fw-bold text-dark mb-2">3. Guarda en tu Mochila</h4>
                <p className="text-secondary small m-0" style={{ fontSize: '0.85rem', lineHeight: '1.4' }}>
                  Revisa las etapas generadas con los alojamientos disponibles en cada parada y guarda el itinerario directamente en tu perfil.
                </p>
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