# Sistema de Gestión de Solicitudes de Vacaciones

Este proyecto es una prueba técnica desarrollada con **Laravel y Blade tradicional**. Permite gestionar solicitudes de vacaciones de empleados con un flujo completo de autenticación, roles, panel de control y módulo de administración.

## Características Principales

*   **Autenticación con Roles:** Administrador, Supervisor y Empleado.
*   **Módulo de Empleados:** CRUD completo para la administración de usuarios, asignación de supervisores y búsqueda/filtrado avanzado.
*   **Módulo de Vacaciones:** 
    *   Los empleados pueden solicitar vacaciones (cálculo automático de días y validaciones).
    *   Pueden cancelar sus solicitudes si aún no han sido respondidas.
*   **Vistas Dinámicas por Rol:**
    *   **Supervisor:** Solo ve y gestiona (aprueba/rechaza con observaciones) las solicitudes de sus empleados a cargo.
    *   **Administrador:** Ve todas las solicitudes, tiene filtros avanzados y gestiona a todos los empleados.
*   **Dashboard Inteligente:** Indicadores dinámicos y tabla de resumen en base al rol del usuario conectado.

## Requisitos Previos

*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   Base de datos SQLite (configurada por defecto) o MySQL.

## Instrucciones de Instalación

Sigue estos pasos para levantar el proyecto en tu entorno local:

1. **Clonar el repositorio**
   (Si aplica) `git clone <url-del-repositorio>`
   `cd SolicitudesVacaciones`

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar y compilar dependencias de Frontend (Tailwind y Alpine.js)**
   ```bash
   npm install
   npm run build
   ```

4. **Configurar las variables de entorno**
   Copia el archivo de ejemplo para crear tu `.env`:
   ```bash
   cp .env.example .env
   ```
   *Nota: Por defecto, está configurado para usar SQLite, por lo que no necesitas configurar credenciales de MySQL para probar el sistema rápidamente.*

5. **Generar la clave de la aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y poblar la base de datos (Seeders)**
   Esto creará las tablas necesarias y cargará los usuarios de prueba.
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Levantar el servidor local**
   ```bash
   php artisan serve
   ```
   La aplicación estará disponible en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Usuarios de Prueba Generados (Seeders)

La base de datos viene precargada con 3 usuarios listos para probar los diferentes flujos. Todos utilizan la misma contraseña.

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@prueba.com` | `password0828` |
| **Supervisor** | `supervisor@prueba.com` | `password0828` |
| **Empleado** | `empleado@prueba.com` | `password0828` |

## Decisiones Técnicas y Buenas Prácticas

*   **Rutas Agrupadas:** Se utilizó `Route::middleware` y grupos para proteger las rutas administrativas. Se creó el middleware `CheckRole`.
*   **Arquitectura MVC:** Uso de controladores Resource para empleados y controladores limpios para vacaciones y el dashboard.
*   **Frontend:** Se usó TailwindCSS nativo de Laravel Breeze para la interfaz visual, logrando un aspecto limpio, moderno y responsivo. Se integró **Alpine.js** (incluido en Breeze) para manejar de forma reactiva las ventanas modales de aprobación/rechazo sin necesidad de recargar la página o instalar librerías pesadas.
