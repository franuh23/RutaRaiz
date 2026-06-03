import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import Button from '../components/ui/Button';
import TarjetaClima from '../components/seguimiento/TarjetaClima';
import LineaProgreso from '../components/seguimiento/LineaProgreso';
import { apiFetch } from '../services/api';

export default function SeguimientoPage() {
    const { token, loading } = useAuth();
    const navigate = useNavigate();
    const [rutaActiva, setRutaActiva] = useState(null);
    const [cargando, setCargando] = useState(true);
    const [parando, setParando] = useState(false);

    useEffect(() => {
        if (loading) return;
        if (!token) {
            navigate('/login');
            return;
        }

        // 🚀 SIMPLIFICADO: Buscamos directamente las planificaciones para pescar la activa
        apiFetch('/api/planificaciones', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                const lista = data.data || [];
                const activa = lista.find(p => p.en_curso);

                if (activa) {
                    // Cargamos el detalle completo con el ordenamiento SQL reparado del show
                    apiFetch(`/api/planificaciones/${activa.id}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(detalle => {
                            setRutaActiva(detalle.data || null);
                            setCargando(false);
                        });
                } else {
                    setRutaActiva(null);
                    setCargando(false);
                }
            })
            .catch(err => {
                console.error("Error en el panel de seguimiento:", err);
                setCargando(false);
            });
    }, [token, loading, navigate]);

    // Función interactiva para marcar/desmarcar los checks de los días
    const handleToggleEtapa = async (etapaId) => {
        if (!rutaActiva) return;
        try {
            const response = await apiFetch(`/api/planificaciones/${rutaActiva.id}/etapas/${etapaId}/toggle`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();

            if (response.ok) {
                // Modificamos el estado manteniendo estrictamente la inmutabilidad para que no salten de posición
                setRutaActiva(prev => ({
                    ...prev,
                    etapas: prev.etapas.map(e => e.id === etapaId ? { ...e, completada: !!data.completada } : e)
                }));
            }
        } catch (err) {
            console.error("Error al actualizar hito de progreso:", err);
        }
    };

    // 🚀 NUEVA FUNCIÓN: Detener el camino por completo
    const handlePararRuta = async () => {
        if (!rutaActiva || !window.confirm('¿Seguro que deseas detener el seguimiento de esta ruta? Puedes reanudarla cuando quieras.')) return;
        setParando(true);
        try {
            const response = await apiFetch(`/api/planificaciones/${rutaActiva.id}/parar`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                setRutaActiva(null); // Reseteamos la pantalla al muro de contingencia
            }
        } catch (err) {
            console.error("Error al parar la ruta:", err);
        } finally {
            setParando(false);
        }
    };

    // Nueva función para el fin del camino
    const handleFinalizarRuta = async () => {
        if (!window.confirm('🎉 ¡Felicidades! ¿Deseas dar por concluido este viaje y guardarlo en tu historial de éxitos?')) return;
        setParando(true);
        try {
            const response = await apiFetch(`/api/planificaciones/${rutaActiva.id}/finalizar`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                alert('¡Itinerario completado! Tu registro se ha guardado en el historial de RutaRaíz. 🥾🏆');
                setRutaActiva(null);
                navigate('/mis-planificaciones');
            }
        } catch (err) {
            console.error(err);
        } finally {
            setParando(false);
        }
    };

    if (loading || cargando) {
        return (
            <Container>
                <div className="text-center py-5 text-muted small">Abriendo tu mapa de seguimiento activo...</div>
            </Container>
        );
    }

    if (!rutaActiva) {
        return (
            <Container>
                <div className="text-center py-5 my-4 bg-white shadow-sm p-5" style={{ borderRadius: 'var(--radius-lg)' }}>
                    <div className="text-muted mb-3 fs-1">🧭</div>
                    <h2 className="fw-bold h4 mb-2" style={{ color: 'var(--verde-bosque)' }}>No tienes ninguna ruta en curso</h2>
                    <p className="text-muted col-md-6 mx-auto mb-4 small">
                        Para poder hacer el seguimiento de tu itinerario en tiempo real, marcar las etapas completadas y monitorizar el clima, primero debes activar una ruta desde tu mochila.
                    </p>
                    <Button variant="primary" onClick={() => navigate('/mis-planificaciones')} style={{ background: 'var(--verde-bosque)' }}>
                        Ir a mis planificaciones
                    </Button>
                </div>
            </Container>
        );
    }

    const etapasTotales = rutaActiva.etapas?.length || 0;
    const etapasCompletadas = rutaActiva.etapas?.filter(e => e.completada).length || 0;
    const porcentajeProgreso = etapasTotales > 0 ? Math.round((etapasCompletadas / etapasTotales) * 100) : 0;

    // Buscamos cuál es la primera etapa vacía para pintarle el clima en la meta
    const etapaActualClima = rutaActiva.etapas?.find(e => !e.completada) || rutaActiva.etapas?.[etapasTotales - 1];
    const puebloClima = etapaActualClima ? etapaActualClima.fin : null;

    return (
        <Container>
            <div className="my-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <span className="text-uppercase fw-bold text-muted small tracking-wider">Panel del Peregrino</span>
                    <h1 className="h2 m-0 fw-bold text-dark">
                        Seguimiento: <span style={{ color: 'var(--verde-bosque)' }}>{rutaActiva.ruta_nombre}</span>
                    </h1>
                </div>
                
                {/* 🚀 BOTÓN DINÁMICO INTELIGENTE */}
                {porcentajeProgreso === 100 ? (
                    <Button 
                        variant="primary" 
                        onClick={handleFinalizarRuta} 
                        disabled={parando}
                        className="px-4 fw-bold shadow animate__animated animate__bounceIn"
                        style={{ borderRadius: 'var(--radius-md)', backgroundColor: '#ffc107', color: '#000', border: 'none' }}
                    >
                        🏆 FINALIZAR CAMINO
                    </Button>
                ) : (
                    <Button 
                        variant="outline" 
                        onClick={handlePararRuta} 
                        disabled={parando}
                        className="btn-outline-danger px-4 border-2 fw-bold"
                        style={{ borderRadius: 'var(--radius-md)' }}
                    >
                        {parando ? 'Parando...' : '🛑 Detener seguimiento'}
                    </Button>
                )}
            </div>

            {/* 📊 SECCIÓN DE MÉTRICAS GENERALES */}
            <div className="card border-0 shadow-sm p-4 bg-white mb-4" style={{ borderRadius: 'var(--radius-lg)' }}>
                <div className="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <span className="fw-bold text-muted small">PROGRESO DEL CAMINO</span>
                    <span className="h5 mb-0 fw-extrabold text-success">{porcentajeProgreso}%</span>
                </div>
                <div className="progress mb-3" style={{ height: '12px', borderRadius: '6px', backgroundColor: '#edf2f7' }}>
                    <div
                        className="progress-bar"
                        role="progressbar"
                        style={{
                            width: `${porcentajeProgreso}%`,
                            backgroundColor: 'var(--verde-medio)',
                            transition: 'width 0.4s ease',
                            borderRadius: '6px'
                        }}
                        aria-valuenow={porcentajeProgreso}
                        aria-valuemin="0"
                        aria-valuemax="100"
                    ></div>
                </div>
                <div className="d-flex gap-3 text-muted small fw-medium">
                    <span>🏁 Total: <strong>{rutaActiva.total_km} km</strong></span>
                    <span>🗓️ Jornadas: <strong>{etapasCompletadas} / {etapasTotales} hechas</strong></span>
                </div>
            </div>

            <div className="row g-4 mb-5">
                <div className="col-12 col-lg-8">
                    <div className="bg-white p-4 shadow-sm" style={{ borderRadius: 'var(--radius-lg)' }}>
                        <h3 className="h6 fw-bold text-muted text-uppercase mb-4" style={{ fontSize: '12px', letterSpacing: '0.05em' }}>
                            🗺️ Hoja de Ruta e Hitos de Paso
                        </h3>
                        <LineaProgreso etapas={rutaActiva.etapas} onToggle={handleToggleEtapa} />
                    </div>
                </div>

                <div className="col-12 col-lg-4">
                    <h3 className="h6 fw-bold text-muted text-uppercase mb-3" style={{ fontSize: '12px', letterSpacing: '0.05em' }}>
                        🌤️ Meteorología en Ruta
                    </h3>
                    <TarjetaClima pueblo={puebloClima} />
                </div>
            </div>
        </Container>
    );
}