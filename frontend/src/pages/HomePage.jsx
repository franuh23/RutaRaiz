import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Container from '../components/layout/Container';
import HeroSection from '../components/sections/HeroSection';
import RoutesPreviewSection from '../components/sections/RoutesPreviewSection';
import StatsGeneralSection from '../components/sections/StatsGeneralSection';
import { apiFetch } from '../services/api';
// Componente de la página de inicio, recopila mediante Promise.all.

export default function HomePage() {
  const navigate = useNavigate();
  const [rutasTotales, setRutasTotales] = useState([]);
  const [rutasDestacadas, setRutasDestacadas] = useState([]);
  const [totalLocs, setTotalLocs] = useState(0);
  const [totalAlojs, setTotalAlojs] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      apiFetch('/api/rutas').then(res => res.json()),
      apiFetch('/api/localizaciones').then(res => res.json()),
      apiFetch('/api/alojamientos').then(res => res.json())
    ])
      .then(([rutasData, locsData, alojsData]) => {
        const todasLasRutas = rutasData.data || [];
        setRutasTotales(todasLasRutas);

        const idsDeseados = [1, 12, 13];
        const filtradas = todasLasRutas.filter(ruta => idsDeseados.includes(Number(ruta.id)));
        
        setRutasDestacadas(filtradas);
        setTotalLocs((locsData.data || []).length);
        setTotalAlojs((alojsData.data || []).length);
        setLoading(false);
      })
      .catch(err => {
        console.error('Error cargando estadísticas de la Home:', err);
        setLoading(false);
      });
  }, []);

  if (loading) {
    return (
      <Container>
        <div className="text-center py-5 text-muted small">Cargando portada de RutaRaíz...</div>
      </Container>
    );
  }

  return (
    <>
      <HeroSection onPlanificar={() => navigate('/planificador')} />
      {rutasDestacadas.length > 0 && (
        <RoutesPreviewSection rutas={rutasDestacadas} />
      )}
      <StatsGeneralSection 
        rutas={rutasTotales} 
        totalLocalizaciones={totalLocs} 
        totalAlojamientos={totalAlojs} 
      />
    </>
  );
}