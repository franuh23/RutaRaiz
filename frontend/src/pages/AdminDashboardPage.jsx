import React, { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';

export default function AdminDashboardPage() {
  const { token } = useAuth();
  const [stats, setStats] = useState({ rutas: 0, usuarios: 0, planificaciones: 0 });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simulación de carga de métricas de gestión interna de la BD
    Promise.all([
      fetch('/api/rutas').then(res => res.json()),
      fetch('/api/planificaciones', { headers: { 'Authorization': `Bearer ${token}` } }).then(res => res.json())
    ])
      .then(([rutasData, planifData]) => {
        setStats({
          rutas: rutasData.data?.length || 0,
          planificaciones: planifData.data?.length || 0,
          usuarios: 'Módulo Blade'
        });
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [token]);

  if (loading) return <Container><div className="text-center py-5">Cargando panel...</div></Container>;

  return (
    <Container>
      <div className="my-4 p-4 bg-light rounded shadow-sm">
        <h1 className="h3 mb-2 text-dark fw-bold">Panel de Administración</h1>
        <p className="text-muted small">Control de recursos globales del proyecto RutaRaíz</p>
      </div>

      <div className="row g-3 mb-4">
        {[
          { title: 'Rutas Activas', value: stats.rutas, color: 'var(--verde-bosque)' },
          { title: 'Planificaciones Totales', value: stats.planificaciones, color: 'var(--verde-medio)' },
          { title: 'Gestión Usuarios', value: stats.usuarios, color: 'var(--tierra)' }
        ].map((card, idx) => (
          <div key={idx} className="col-12 col-md-4">
            <div className="card h-100 border-0 shadow-sm p-3">
              <span className="text-uppercase text-muted fw-bold small mb-1">{card.title}</span>
              <span className="h3 mb-0 fw-bold" style={{ color: card.color }}>{card.value}</span>
            </div>
          </div>
        ))}
      </div>

      <div className="card border-0 shadow-sm p-4 mb-5">
        <h2 className="h5 fw-bold mb-3">Acceso rápido a operaciones CRUD (Backend Blade)</h2>
        <p className="text-muted small mb-3">
          Para cumplir las directrices intermodulares, las altas, bajas y modificaciones estructurales de rutas y alojamientos se gestionan a través de las plantillas seguras del servidor.
        </p>
        <div className="d-flex flex-wrap gap-2">
          <a href="/rutas" className="btn btn-sm text-white px-3" style={{ background: 'var(--verde-bosque)' }}>Gestionar Rutas</a>
          <a href="/alojamientos" className="btn btn-sm text-white px-3" style={{ background: 'var(--verde-medio)' }}>Gestionar Alojamientos</a>
          <a href="/localizaciones" className="btn btn-sm text-white px-3" style={{ background: 'var(--tierra)' }}>Gestionar Localizaciones</a>
        </div>
      </div>
    </Container>
  );
}