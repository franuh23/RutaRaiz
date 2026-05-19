import HeroSection from '../components/sections/HeroSection';
import HowItWorksSection from '../components/sections/HowItWorksSection';
import FeaturesSection from '../components/sections/FeaturesSection';
import RoutesPreviewSection from '../components/sections/RoutesPreviewSection';
import CTASection from '../components/sections/CTASection';

export default function HomePage() {
  return (
    <main>
      <HeroSection />
      <HowItWorksSection />
      <FeaturesSection />
      <RoutesPreviewSection />
      <CTASection />
    </main>
  );
}
