import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import styles from './PlanificadorPage.module.css';

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

  // Cargar rutas al inicio
  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => setRutas(data.data))
      .catch(err => console.error(err));
  }, []);

  // Cuando se selecciona una ruta, cargar sus localizaciones
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

  // Calcular etapas (sin guardar)
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

  // Guardar planificación en BD
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
      <h1 className={styles.titulo}>Planificador de rutas</h1>

      <form onSubmit={handleSubmit} className={styles.form}>

        <div className={styles.formGroup}>
          <label>Ruta:</label>
          <select value={selectedRuta} onChange={(e) => setSelectedRuta(e.target.value)} required>
            <option value="">Selecciona una ruta</option>
            {rutas.map(ruta => (
              <option key={ruta.id} value={ruta.id}>{ruta.nombre}</option>
            ))}
          </select>
        </div>

        <div className={styles.formGroup}>
          <label>Punto de inicio:</label>
          <select value={inicioId} onChange={(e) => setInicioId(e.target.value)} required>
            <option value="">Selecciona inicio</option>
            {localizaciones.map(loc => (
              <option key={loc.id} value={loc.id}>
                {loc.nombre} ({loc.distancia_desde_inicio} km)
              </option>
            ))}
          </select>
        </div>

        <div className={styles.formGroup}>
          <label>Punto de fin (opcional):</label>
          <select value={finId} onChange={(e) => setFinId(e.target.value)}>
            <option value="">Hasta el final</option>
            {localizaciones.map(loc => (
              <option key={loc.id} value={loc.id}>
                {loc.nombre} ({loc.distancia_desde_inicio} km)
              </option>
            ))}
          </select>
        </div>

        <div className={styles.formGroup}>
          <label>Kilómetros por día:</label>
          <input
            type="number"
            value={kmDia}
            onChange={(e) => setKmDia(e.target.value)}
            min="1"
            max="100"
            required
          />
        </div>

        <div className={styles.formGroup}>
          <label>Fecha de inicio (necesaria para guardar):</label>
          <input
            type="date"
            value={fechaInicio}
            onChange={(e) => setFechaInicio(e.target.value)}
          />
        </div>

        <button type="submit" className={styles.button} disabled={loading}>
          {loading ? 'Calculando...' : 'Calcular etapas'}
        </button>

      </form>

      {error && <div className={styles.error}>{error}</div>}

      {etapas && (
        <div className={styles.resultado}>
          <h2>Resultado</h2>
          <div className={styles.total}>
            {etapas.total_km} km en {etapas.dias_totales} días
          </div>

          <ul className={styles.etapasLista}>
            {etapas.etapas?.map(etapa => (
              <li key={etapa.dia} className={styles.etapaItem}>
                <strong>Día {etapa.dia}:</strong> {etapa.inicio} → {etapa.fin} ({etapa.distancia} km)
              </li>
            ))}
          </ul>

          {mensajeGuardado ? (
            <div className={styles.exito}>
              {mensajeGuardado}
              <button
                className={styles.btnVerPlanificaciones}
                onClick={() => navigate('/mis-planificaciones')}
              >
                Ver mis planificaciones
              </button>
            </div>
          ) : (
            <button
              className={styles.btnGuardar}
              onClick={handleGuardar}
              disabled={guardando}
            >
              {guardando ? 'Guardando...' : '💾 Guardar planificación'}
            </button>
          )}
        </div>
      )}

    </Container>
  );
}