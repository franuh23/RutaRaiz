import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/layout/Navbar';
import Footer from './components/layout/Footer';
import HomePage from './pages/HomePage';
import RutasPage from './pages/RutasPage';
import RutaDetailPage from './pages/RutaDetailPage';
import PlanificadorPage from './pages/PlanificadorPage';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';
import MisPlanificacionesPage from './pages/MisPlanificacionesPage';
import PlanificacionDetallePage from './pages/PlanificacionDetallePage';
import AdminDashboardPage from './pages/AdminDashboardPage';
import ProtectedRoute from './components/auth/ProtectedRoute';

export default function App() {
  return (
    <BrowserRouter>
      <Navbar />
      <main style={{ minHeight: '80vh' }}>
        <Routes>
          {/* Rutas Públicas */}
          <Route path="/" element={<HomePage />} />
          <Route path="/rutas" element={<RutasPage />} />
          <Route path="/rutas/:id" element={<RutaDetailPage />} />
          <Route path="/planificador" element={<PlanificadorPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />

          {/* Rutas Protegidas: Cualquier usuario logueado */}
          <Route path="/mis-planificaciones" element={<ProtectedRoute><MisPlanificacionesPage /></ProtectedRoute>} />
          <Route path="/mis-planificaciones/:id" element={<ProtectedRoute><PlanificacionDetallePage /></ProtectedRoute>} />

          {/* Rutas Protegidas de Administración: Solo rol 'admin' */}
          <Route 
            path="/admin" 
            element={
              <ProtectedRoute allowedRoles={['admin']}>
                <AdminDashboardPage />
              </ProtectedRoute>
            } 
          />
        </Routes>
      </main>
      <Footer />
    </BrowserRouter>
  );
}