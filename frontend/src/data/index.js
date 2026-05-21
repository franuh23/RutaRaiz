export const RUTAS_DESTACADAS = [
  {
    id: 1,
    nombre: "GR 11 · Senda Pirenaica",
    zona: "Pirineos",
    kilometros: 840,
    dificultad: "difícil",
    etiqueta: "Épica",
    emoji: "⛰️",
    gradient: "linear-gradient(135deg, #2d5a1b 0%, #1e3a0f 100%)",
    descripcion: "Cruza los Pirineos de costa a costa por el lado español. Una aventura de semanas.",
  },
  {
    id: 2,
    nombre: "Camino del Cid · GR 160",
    zona: "España",
    kilometros: 1500,
    dificultad: "media",
    etiqueta: "Histórica",
    emoji: "⚔️",
    gradient: "linear-gradient(135deg, #7a3b10 0%, #a0522d 100%)",
    descripcion: "Sigue las huellas del Cid Campeador a través de paisajes castellanos únicos.",
  },
  {
    id: 3,
    nombre: "Carros de Foc",
    zona: "Aigüestortes",
    kilometros: 65,
    dificultad: "difícil",
    etiqueta: "Montaña",
    emoji: "🏔️",
    gradient: "linear-gradient(135deg, #3d7a25 0%, #2d5a1b 100%)",
    descripcion: "Ruta circular de alta montaña pasando por los refugios más icónicos del Pirineo catalán.",
  },
];

export const FEATURES = [
  {
    id: 1,
    icono: "🗓️",
    titulo: "Planificador por etapas",
    descripcion: "Personaliza tu ruta según días disponibles, ritmo y preferencias físicas.",
  },
  {
    id: 2,
    icono: "🏠",
    titulo: "Alojamientos",
    descripcion: "Descubre dónde pernoctar con valoraciones reales de la comunidad senderista.",
  },
  {
    id: 3,
    icono: "📍",
    titulo: "Checkpoints",
    descripcion: "Registra tu progreso y comparte tu ubicación en tiempo real con familia.",
  },
  {
    id: 4,
    icono: "💬",
    titulo: "Comunidad",
    descripcion: "Comparte experiencias, resuelve dudas y conecta con otros aventureros.",
  },
];

export const PASOS = [
  {
    numero: 1,
    titulo: "Crea tu cuenta",
    descripcion: "Regístrate gratis y accede a todas las funcionalidades de planificación.",
  },
  {
    numero: 2,
    titulo: "Elige tus parámetros",
    descripcion: "Días disponibles, km por día, punto de partida y destino final.",
  },
  {
    numero: 3,
    titulo: "Obtén tu ruta",
    descripcion: "Te sugerimos alojamientos, paradas y puntos de interés para cada etapa.",
  },
];

export const NAV_LINKS = [
  { label: "Rutas",        href: "/rutas" },
  { label: "Planificador", href: "/planificador" },
  { label: "Comunidad",    href: "/comunidad" },
  { label: "Ranking",      href: "/ranking" },
];

export const FOOTER_COLS = [
  {
    titulo: "RutaRaíz",
    links: [
      { label: "Sobre nosotros", href: "#" },
      { label: "Cómo funciona",  href: "#" },
      { label: "Contacto",       href: "#" },
    ],
  },
  {
    titulo: "Explorar",
    links: [
      { label: "Rutas GR",           href: "#" },
      { label: "Camino de Santiago",  href: "#" },
      { label: "Vías Verdes",         href: "#" },
    ],
  },
  {
    titulo: "Comunidad",
    links: [
      { label: "Foro",          href: "#" },
      { label: "Ranking",       href: "#" },
      { label: "Experiencias",  href: "#" },
    ],
  },
  {
    titulo: "Ayuda",
    links: [
      { label: "Preguntas frecuentes", href: "#" },
      { label: "Privacidad",           href: "#" },
      { label: "Términos",             href: "#" },
    ],
  },
];
