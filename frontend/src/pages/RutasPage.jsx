import { useEffect, useState } from 'react';
import Container from '../components/layout/Container';
import RouteCardSimple from '../components/ui/RouteCardSimple';

export default function RutasPage() {
  const [rutas, setRutas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/rutas')
      .then(res => res.json())
      .then(data => {
        setRutas(data.data);
        setLoading(false);
      })
      .catch(err => console.error(err));
  }, []);

  if (loading) return <Container><p>Cargando rutas...</p></Container>;

  return (
    <Container>
      <h1>Todas las rutas</h1>
      <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
        {rutas.map(ruta => (
          <RouteCardSimple key={ruta.id} ruta={ruta} />
        ))}
      </div>
    </Container>
  );
}