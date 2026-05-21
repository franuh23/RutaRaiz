import { BrowserRouter, Routes, Route } from 'react-router-dom';
import './styles/globals.css';
import Navbar from './components/layout/Navbar';
import Footer from './components/layout/Footer';
import HomePage from './pages/HomePage';
import RutasPage from './pages/RutasPage';
import RutaDetailPage from './pages/RutaDetailPage';
import PlanificadorPage from './pages/PlanificadorPage';

export default function App() {
  return (
    <BrowserRouter>
      <Navbar />
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/rutas" element={<RutasPage />} />
        <Route path="/rutas/:id" element={<RutaDetailPage />} />
        <Route path="/planificador" element={<PlanificadorPage />} />
      </Routes>
      <Footer />
    </BrowserRouter>
  );
}