import React, { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import Container from '../components/layout/Container';
import AdminRutaRow from '../components/admin/AdminRutaRow';
import AdminRutaModal from '../components/admin/AdminRutaModal';
import AdminLocalizacionModal from '../components/admin/AdminLocalizacionModal';
import AdminAlojamientoModal from '../components/admin/AdminAlojamientoModal';
import AdminLocalizacionesAccordion from '../components/admin/AdminLocalizacionesAccordion';
import AdminAlojamientosAccordion from '../components/admin/AdminAlojamientosAccordion';

export default function AdminDashboardPage() {
  const { token } = useAuth();
  const [activeTab, setActiveTab] = useState('rutas');
  const [rutas, setRutas] = useState([]);
  const [localizaciones, setLocalizaciones] = useState([]);
  const [alojamientos, setAlojamientos] = useState([]);
  const [loading, setLoading] = useState(true);
  
  // Modales Control Estado
  const [isRutaModalOpen, setIsRutaModalOpen] = useState(false);
  const [rutaEditando, setRutaEditando] = useState(null);
  const [isLocModalOpen, setIsLocModalOpen] = useState(false);
  const [locEditando, setLocEditando] = useState(null);
  const [isAlojModalOpen, setIsAlojModalOpen] = useState(false);
  const [alojamientoEditando, setAlojamientoEditando] = useState(null);

  const cargarDatos = () => {
    Promise.all([
      fetch('/api/rutas').then(res => res.json()),
      fetch('/api/localizaciones').then(res => res.json()),
      fetch('/api/alojamientos').then(res => res.json())
    ])
      .then(([rutasData, locData, alojData]) => {
        setRutas(rutasData.data || []);
        setLocalizaciones(locData.data || []);
        setAlojamientos(alojData.data || []);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  };

  useEffect(() => {
    if (token) cargarDatos();
  }, [token]);

  const handleEliminarRuta = async (id) => {
    if (!confirm('¿Seguro que deseas eliminar esta ruta de forma permanente?')) return;
    try {
      const res = await fetch(`/api/rutas/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` } });
      if (res.ok) cargarDatos();
    } catch (err) { console.error(err); }
  };

  const handleEliminarLoc = async (id) => {
    if (!confirm('¿Seguro que deseas eliminar este hito geográfico?')) return;
    try {
      const res = await fetch(`/api/localizaciones/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` } });
      if (res.ok) cargarDatos();
    } catch (err) { console.error(err); }
  };

  const handleEliminarAloj = async (id) => {
    if (!confirm('¿Seguro que deseas eliminar este alojamiento?')) return;
    try {
      const res = await fetch(`/api/alojamientos/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}` } });
      if (res.ok) cargarDatos();
    } catch (err) { console.error(err); }
  };

  const handleSaveRuta = async (formData) => {
    const esEdicion = !!rutaEditando;
    const url = esEdicion ? `/api/rutas/${rutaEditando.id}` : '/api/rutas';
    try {
      const res = await fetch(url, {
        method: esEdicion ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
        body: JSON.stringify(formData)
      });
      if (res.ok) { setIsRutaModalOpen(false); setRutaEditando(null); cargarDatos(); }
    } catch (err) { console.error(err); }
  };

  const handleSaveLoc = async (formData) => {
    const esEdicion = !!locEditando;
    const url = esEdicion ? `/api/localizaciones/${locEditando.id}` : '/api/localizaciones';
    try {
      const res = await fetch(url, {
        method: esEdicion ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
        body: JSON.stringify(formData)
      });
      if (res.ok) { setIsLocModalOpen(false); setLocEditando(null); cargarDatos(); }
    } catch (err) { console.error(err); }
  };

  const handleSaveAloj = async (formData) => {
    const esEdicion = !!alojamientoEditando;
    const url = esEdicion ? `/api/alojamientos/${alojamientoEditando.id}` : '/api/alojamientos';
    try {
      const res = await fetch(url, {
        method: esEdicion ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
        body: JSON.stringify(formData)
      });
      if (res.ok) { setIsAlojModalOpen(false); setAlojamientoEditando(null); cargarDatos(); }
    } catch (err) { console.error(err); }
  };

  if (loading) return <Container><div className="text-center py-5 text-muted">Cargando panel estructural...</div></Container>;

  return (
    <Container>
      <div className="my-4 p-4 bg-light rounded shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h1 className="h3 m-0 text-dark fw-bold">Panel de Administración</h1>
          <p className="text-muted small m-0">Mantenimiento global de entidades relacionales</p>
        </div>
        <div>
          {activeTab === 'rutas' && (
            <button className="btn text-white fw-bold px-4" onClick={() => { setRutaEditando(null); setIsRutaModalOpen(true); }} style={{ background: 'var(--verde-bosque)', borderRadius: 'var(--radius-md)' }}>+ Agregar Ruta</button>
          )}
          {activeTab === 'localizaciones' && (
            <button className="btn text-white fw-bold px-4" onClick={() => { setLocEditando(null); setIsLocModalOpen(true); }} style={{ background: 'var(--verde-medio)', borderRadius: 'var(--radius-md)' }}>+ Agregar Localización</button>
          )}
          {activeTab === 'alojamientos' && (
            <button className="btn text-white fw-bold px-4" onClick={() => { setAlojamientoEditando(null); setIsAlojModalOpen(true); }} style={{ background: 'var(--tierra)', borderRadius: 'var(--radius-md)' }}>+ Agregar Alojamiento</button>
          )}
        </div>
      </div>

      {/* Tabs Selector */}
      <ul className="nav nav-tabs mb-4 border-0 bg-light p-2 rounded">
        <li className="nav-item">
          <button className={`nav-link border-0 fw-bold px-4 py-2 ${activeTab === 'rutas' ? 'bg-white text-dark shadow-sm' : 'text-secondary'}`} onClick={() => setActiveTab('rutas')}>🗺️ Rutas ({rutas.length})</button>
        </li>
        <li className="nav-item">
          <button className={`nav-link border-0 fw-bold px-4 py-2 ${activeTab === 'localizaciones' ? 'bg-white text-dark shadow-sm' : 'text-secondary'}`} onClick={() => setActiveTab('localizaciones')}>📍 Localizaciones ({localizaciones.length})</button>
        </li>
        <li className="nav-item">
          <button className={`nav-link border-0 fw-bold px-4 py-2 ${activeTab === 'alojamientos' ? 'bg-white text-dark shadow-sm' : 'text-secondary'}`} onClick={() => setActiveTab('alojamientos')}>🏠 Alojamientos ({alojamientos.length})</button>
        </li>
      </ul>

      {/* Renderizado Condicional de Tablas o Acordeones según la pestaña activa */}
      <div className="mb-5">
        {activeTab === 'rutas' && (
          <div className="card border-0 shadow-sm p-4 bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
            <div className="table-responsive">
              <table className="table table-hover border-top m-0">
                <thead className="table-light text-uppercase small text-muted">
                  <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Dificultad</th><th>Distancia</th><th className="text-end">Acciones</th></tr>
                </thead>
                <tbody>
                  {rutas.map(ruta => (
                    <AdminRutaRow key={ruta.id} ruta={ruta} onEditar={(r) => { setRutaEditando(r); setIsRutaModalOpen(true); }} onEliminar={handleEliminarRuta} />
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {activeTab === 'localizaciones' && (
          <AdminLocalizacionesAccordion 
            rutas={rutas} 
            localizaciones={localizaciones} 
            onEditar={(l) => { setLocEditando(l); setIsLocModalOpen(true); }} 
            onEliminar={handleEliminarLoc} 
          />
        )}

        {activeTab === 'alojamientos' && (
          <AdminAlojamientosAccordion 
            rutas={rutas} 
            localizaciones={localizaciones} 
            alojamientos={alojamientos} 
            onEditar={(a) => { setAlojamientoEditando(a); setIsAlojModalOpen(true); }} 
            onEliminar={handleEliminarAloj} 
          />
        )}
      </div>

      <AdminRutaModal isOpen={isRutaModalOpen} onClose={() => { setIsRutaModalOpen(false); setRutaEditando(null); }} onSave={handleSaveRuta} rutaEditando={rutaEditando} />
      <AdminLocalizacionModal isOpen={isLocModalOpen} onClose={() => { setIsLocModalOpen(false); setLocEditando(null); }} onSave={handleSaveLoc} locEditando={locEditando} rutas={rutas} />
      <AdminAlojamientoModal isOpen={isAlojModalOpen} onClose={() => { setIsAlojModalOpen(false); setAlojamientoEditando(null); }} onSave={handleSaveAloj} alojamientoEditando={alojamientoEditando} localizaciones={localizaciones} />
    </Container>
  );
}