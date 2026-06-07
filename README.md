# Nombre del proyecto
RutaRaiz

## Funcionalidades
**RutaRaiz** se trata de una aplicación web desarrollada como trabajo de fin de grado de DAW.
Su objetivo principal es la gestión, planificación y seguimiento en tiempo real de rutas de senderismo de larga distancia (como el GR11), permitiendo organizar etapas, controlar alojamientos y registrar pernoctas.

---

## Características Principales

- **Planificador de Itinerarios:** Permite estructurar etapas paso a paso calculando distancias.
- **Base de Datos de Rutas:** Seeders con información real de rutas (GR11, Caminos de Santiago, etc.) y alojamientos.
- **Seguimiento en Tiempo Real:** Visualización de progreso y tarjetas de información meteorológica.
- **Comunidad y Reseñas:** Espacio interactivo para ver las planificaciones de otros usuarios y dar likes.
- **Exportación de Planes:** Funcionalidad para descargar planificaciones en formato PDF / Excel.
- **Panel de Administración:** Gestión privada de usuarios, validación de alojamientos y moderación.

---

## Requisitos previos
- Docker y Docker Compose instalados.
- Node.js (versión 18 o superior) y npm.

## Ejecutar el proyecto en local
- Clonar el repositorio.
### Backend
1. Navega al directorio del backend y levanta el entorno contenedorizado.
2. Copia el archivo de entorno de ejemplo si no lo tienes creado -> cp src/.env.example src/.env
3. Levanta los contenedores con Docker Compose -> docker compose up -d
4. Instala dependencias de Composer -> docker compose exec php composer install
5. Genera la clave de la aplicación -> docker compose exec php php artisan key:generate
6. Ejecuta las migraciones y seeders -> docker compose exec php php artisan migrate --seed
### Frontend
1. Navega al directorio del frontend para instalar los paquetes e iniciar el servidor de desarrollo.
2. Instala dependencias del proyecto -> npm install
3. Inicia el servidor de desarrollo con Vite -> npm run dev

---

## Uso
### Local
Acceder a [http://localhost:5173](http://localhost:5173)

## Estructura del Proyecto

### Backend

```

backend/
+---Caddyfile
+---docker-compose.yml
+---php/
|   ---Dockerfile
---src/
+---app/
|   +---Console/
|   |   ---Commands/
|   +---Exports/
|   +---Http/
|   |   +---Controllers/
|   |   |   ---Api/
|   |   +---Middleware/
|   |   +---Requests/
|   |   ---Resources/
|   +---Models/
|   ---Providers/
+---bootstrap/
+---config/
+---database/
|   +---factories/
|   +---migrations/
|   ---seeders/
+---public/
+---resources/
|   +---css/
|   +---js/
|   ---views/
+---routes/
+---storage/
|   +---app/
|   |   +---private/
|   |   ---public/
|   +---framework/
|   |   +---cache/
|   |   |   ---data/
|   |   +---sessions/
|   |   +---testing/
|   |   ---views/
|   ---logs/
+---tests/
+---.env
---vendor/

```
### Frontend

```

frontend/
+---node_modules/
+---public/
|   +---favicon.svg
|   +---icons.svg
|   ---logoRutaRaiz.png
+---.env
+---index.html
+---package.json
+---vite.config.js
---src/
+---components/
|   +---admin/
|   +---alojamientos/
|   +---auth/
|   +---comunidad/
|   +---layout/
|   +---planificacion/
|   +---profile/
|   +---sections/
|   +---seguimiento/
|   ---ui/
+---context/
+---data/
+---pages/
---services/

```

## Contribución
1. Hacer un fork del repositorio.
2. Crear la rama correspondiente con el formato `feature/funcionalidad` o `fix/arreglo`
 ```bash
 git checkout -b feature/nueva-funcionalidad

```

3. Una vez finalices de implementar los cambios, se deberá realizar un pull request y solicitar un merge.

```

```
