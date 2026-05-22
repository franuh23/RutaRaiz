import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import Container from '../components/layout/Container';
import HeroSection from '../components/sections/HeroSection';
import RoutesPreviewSection from '../components/sections/RoutesPreviewSection';
import StatsGeneralSection from '../components/sections/StatsGeneralSection';

export default function HomePage() {
  const navigate = useNavigate();
  const [rutasTotales, setRutasTotales] = useState([]);
  const [rutasDestacadas, setRutasDestacadas] = useState([]);
  const [totalLocs, setTotalLocs] = useState(0);
  const [totalAlojs, setTotalAlojs] = useState(0);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Peticiones concurrentes a tus endpoints de Laravel
    Promise.all([
      fetch('/api/rutas').then(res => res.json()),
      fetch('/api/localizaciones').then(res => res.json()),
      fetch('/api/alojamientos').then(res => res.json())
    ])
      .then(([rutasData, locsData, alojsData]) => {
        const todasLasRutas = rutasData.data || [];
        setRutasTotales(todasLasRutas);

        // Filtramos tus 3 rutas reales preferidas para el escaparate
        const nombresDeseados = ['Camino Primitivo', 'Camino de Santiago Frances', 'Camino del Norte'];
        const filtradas = todasLasRutas.filter(ruta => nombresDeseados.includes(ruta.nombre));
        setRutasDestacadas(filtradas);

        // Guardamos los contadores totales reales basados en la base de datos
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
      {/* Sección Hero: Bienvenida principal compacta */}
      <HeroSection onPlanificar={() => navigate('/planificador')} />
      
      <Container>
        {/* Escaparate Dinámico: Solo tus 3 rutas reales */}
        {rutasDestacadas.length > 0 && (
          <div className="py-3">
            <RoutesPreviewSection rutas={rutasDestacadas} />
          </div>
        )}
        
        {/* Estado General de la Red: Panel de métricas 100% reales sin CSS extra */}
        <div className="py-2">
          <StatsGeneralSection 
            rutas={rutasTotales} 
            totalLocalizaciones={totalLocs} 
            totalAlojamientos={totalAlojs} 
          />
        </div>
      </Container>
    </>
  );
}