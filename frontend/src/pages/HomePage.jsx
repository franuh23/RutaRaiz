import React from 'react';
import { useNavigate } from 'react-router-dom';
import Container from '../components/layout/Container';
import HeroSection from '../components/sections/HeroSection';
import FeaturesSection from '../components/sections/FeaturesSection';
import HowItWorksSection from '../components/sections/HowItWorksSection';
import CTASection from '../components/sections/CTASection';

export default function HomePage() {
  const navigate = useNavigate();

  return (
    <>
      <HeroSection onPlanificar={() => navigate('/planificador')} />
      
      <Container>
        <div className="py-5">
          <FeaturesSection />
        </div>
        
        <div className="py-5 border-top">
          <HowItWorksSection />
        </div>
        
        <div className="py-5">
          <CTASection onPlanificar={() => navigate('/planificador')} />
        </div>
      </Container>
    </>
  );
}