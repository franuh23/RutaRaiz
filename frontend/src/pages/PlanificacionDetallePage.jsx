import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import styles from './PlanificacionDetallePage.module.css';

export default function PlanificacionDetallePage() {
    const { id } = useParams();
    const { token, isAuthenticated, loading } = useAuth();
    const navigate = useNavigate();
    const [planificacion, setPlanificacion] = useState(null);
    const [cargando, setCargando] = useState(true);

    // Si no está logueado, redirigir al login
    useEffect(() => {
        if (!loading && !isAuthenticated) {
            navigate('/login');
        }
    }, [loading, isAuthenticated, navigate]);

    // Cargar detalle de la planificación
    useEffect(() => {
        if (!token) return;

        fetch(`/api/planificaciones/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) throw new Error('No encontrada');
                return res.json();
            })
            .then(data => {
                setPlanificacion(data.data);
                setCargando(false);
            })
            .catch(err => {
                console.error(err);
                navigate('/mis-planificaciones');
            });
    }, [token, id, navigate]);

    if (loading || cargando) {
        return <Container><p className={styles.cargando}>Cargando...</p></Container>;
    }

    if (!planificacion) return null;

    return (
        <Container>
            <div className={styles.header}>
                <button className={styles.btnVolver} onClick={() => navigate('/mis-planificaciones')}>
                    ← Volver
                </button>
                <h1 className={styles.titulo}>{planificacion.ruta_nombre}</h1>
            </div>

            {/* Resumen */}
            <div className={styles.resumen}>
                <div className={styles.resumenItem}>
                    <span className={styles.resumenIcono}>📅</span>
                    <div>
                        <span className={styles.resumenLabel}>Fecha de inicio</span>
                        <span className={styles.resumenValor}>{planificacion.fecha_inicio}</span>
                    </div>
                </div>
                <div className={styles.resumenItem}>
                    <span className={styles.resumenIcono}>👣</span>
                    <div>
                        <span className={styles.resumenLabel}>Kilómetros por día</span>
                        <span className={styles.resumenValor}>{planificacion.km_dia} km</span>
                    </div>
                </div>
                <div className={styles.resumenItem}>
                    <span className={styles.resumenIcono}>🗓️</span>
                    <div>
                        <span className={styles.resumenLabel}>Días totales</span>
                        <span className={styles.resumenValor}>{planificacion.dias_totales} días</span>
                    </div>
                </div>
                <div className={styles.resumenItem}>
                    <span className={styles.resumenIcono}>📍</span>
                    <div>
                        <span className={styles.resumenLabel}>Recorrido</span>
                        <span className={styles.resumenValor}>
                            {planificacion.localizacion_inicio_nombre} → {planificacion.localizacion_fin_nombre || 'Final de ruta'}
                        </span>
                    </div>
                </div>
            </div>

            {/* Etapas */}
            <div className={styles.etapasSeccion}>
                <h2 className={styles.subtitulo}>📋 Etapas del recorrido</h2>

                {planificacion.etapas?.length > 0 ? (
                    <div className={styles.etapasList}>
                        {planificacion.etapas.map((etapa) => (
                            <div key={etapa.id} className={styles.etapaCard}>
                                <div className={styles.etapaDia}>
                                    <span>Día</span>
                                    <strong>{etapa.dia}</strong>
                                </div>
                                <div className={styles.etapaInfo}>
                                    <div className={styles.etapaRuta}>
                                        <span className={styles.etapaPunto}>{etapa.localizacion_inicio_nombre}</span>
                                        <span className={styles.etapaFlecha}>→</span>
                                        <span className={styles.etapaPunto}>{etapa.localizacion_fin_nombre}</span>
                                    </div>
                                    <span className={styles.etapaDistancia}>{etapa.distancia} km</span>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <p className={styles.sinEtapas}>No hay etapas registradas para esta planificación.</p>
                )}
            </div>
        </Container>
    );
}