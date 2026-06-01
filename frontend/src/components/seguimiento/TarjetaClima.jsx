import React, { useEffect, useState } from 'react';

export default function TarjetaClima({ pueblo }) {
  const [clima, setClima] = useState(null);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    if (!pueblo) {
      setCargando(false);
      return;
    }

    setCargando(true);
    // 1. Buscamos latitud y longitud del hito geográfico
    fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(pueblo)}&count=1&language=es`)
      .then(res => res.json())
      .then(geoData => {
        const resultado = geoData.results?.[0];
        if (!resultado) throw new Error('Pueblo no localizado');

        // 2. Conectamos con el satélite meteorológico usando las coordenadas exactas
        return fetch(`https://api.open-meteo.com/v1/forecast?latitude=${resultado.latitude}&longitude=${resultado.longitude}&current_weather=true&timezone=auto`);
      })
      .then(res => res.json())
      .then(weatherData => {
        if (weatherData?.current_weather) {
          setClima(weatherData.current_weather);
        }
        setCargando(false);
      })
      .catch(err => {
        console.error("Error consultando el tiempo externo:", err);
        setCargando(false);
      });
  }, [pueblo]);

  // Traductor rápido de códigos meteorológicos WMO estándares a iconos comprensibles
  const obtenerEstadoCielo = (code) => {
    if (code === 0) return { texto: 'Despejado', icono: 'fa-sun text-warning' };
    if ([1, 2, 3].includes(code)) return { texto: 'Intervalos nubosos', icono: 'fa-cloud-sun text-secondary' };
    if ([45, 48].includes(code)) return { texto: 'Niebla en el tramo', icono: 'fa-smog text-muted' };
    if ([51, 53, 55, 61, 63, 65, 80, 81, 82].includes(code)) return { texto: 'Lluvia / Chubascos', icono: 'fa-cloud-showers-heavy text-primary' };
    if ([71, 73, 75, 77, 85, 86].includes(code)) return { texto: 'Nieve', icono: 'fa-snowflake text-info' };
    if ([95, 96, 99].includes(code)) return { texto: 'Tormenta eléctrica', icono: 'fa-cloud-bolt text-dark' };
    return { texto: 'Variable', icono: 'fa-cloud' };
  };

  if (cargando) {
    return (
      <div className="card border-0 shadow-sm p-4 text-center bg-white" style={{ borderRadius: 'var(--radius-lg)' }}>
        <div className="spinner-border spinner-border-sm text-success" role="status"></div>
        <small className="text-muted d-block mt-2">Sincronizando satélite meteorológico...</small>
      </div>
    );
  }

  if (!clima || !pueblo) {
    return (
      <div className="card border-0 shadow-sm p-4 text-center bg-white text-muted small" style={{ borderRadius: 'var(--radius-lg)' }}>
        ⚠️ Clima temporalmente no disponible para esta localización.
      </div>
    );
  }

  const estado = obtenerEstadoCielo(clima.weathercode);

  return (
    <div className="card border-0 shadow-sm p-4 bg-white text-dark text-center" style={{ borderRadius: 'var(--radius-lg)' }}>
      <span className="fw-bold text-muted small text-uppercase mb-1" style={{ fontSize: '11px' }}>Próxima estación</span>
      <h4 className="fw-bold mb-3" style={{ color: 'var(--verde-bosque)' }}>{pueblo}</h4>
      
      <div className="d-flex align-items-center justify-content-center gap-3 my-2">
        <i className={`fa-solid ${estado.icono}`} style={{ fontSize: '38px' }}></i>
        <span className="fs-1 fw-extrabold m-0 text-dark">{Math.round(clima.temperature)}°C</span>
      </div>

      <span className="badge mt-2 bg-light text-dark fw-bold border py-2" style={{ borderRadius: 'var(--radius-md)' }}>
        {estado.texto.toUpperCase()}
      </span>

      <p className="text-muted small mt-3 mb-0 pt-2 border-top">
        {[51, 53, 55, 61, 63, 65, 80, 81, 82, 95, 96, 99].includes(clima.weathercode) 
          ? '🌧️ El tramo reporta agua. Ajusta bien las fundas de la mochila.' 
          : '🥾 Tiempo estable para la marcha. ¡Buen camino, peregrino!'}
      </p>
    </div>
  );
}