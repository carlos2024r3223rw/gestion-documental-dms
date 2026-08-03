<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# 📂 Sistema de Gestión Documental Corporativo (DMS)

![Estado](https://img.shields.io/badge/Estado-Prototipo_Finalizado-success)
![Framework](https://img.shields.io/badge/Laravel-v11-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?logo=tailwind-css)

Un Sistema de Gestión Documental (DMS) de grado corporativo desarrollado para cumplir con las exigencias técnicas y de seguridad de licitaciones empresariales. Diseñado para centralizar, controlar y asegurar el flujo de documentos de una organización.

## ✨ Características Principales

*   **🔒 Control de Acceso Basado en Roles (RBAC):** Sistema robusto de permisos (Administrador, Revisor, Usuario) para garantizar que solo el personal autorizado apruebe o rechace documentos.
*   **🔄 Flujos de Trabajo (Workflows):** Ciclo de vida completo del documento (*Borrador -> Pendiente de Revisión -> Aprobado/Rechazado*).
*   **📑 Control de Versiones Estricto:** Subida de nuevas versiones (v1.0, v2.0) sin sobreescribir el historial. Trazabilidad completa (quién subió qué y cuándo).
*   **📊 Dashboard Analítico:** Panel de control gerencial con indicadores clave de rendimiento (KPIs) sobre el estado de los documentos.
*   **💅 UI/UX Premium:** Interfaz de usuario diseñada con Tailwind CSS, ofreciendo una experiencia moderna, responsiva y altamente intuitiva.

## 🏗️ Arquitectura Técnica

El sistema está construido bajo el patrón arquitectónico **MVC (Modelo-Vista-Controlador)** utilizando el framework PHP más seguro y moderno del mercado:

*   **Backend:** Laravel 11 (PHP 8.3)
*   **Base de Datos:** Relacional (MySQL/PostgreSQL) a través de Eloquent ORM.
*   **Frontend:** Blade Templates + Tailwind CSS + Vite (Hot Module Replacement).
*   **Autenticación:** Laravel Breeze (Sesiones seguras y encriptación nativa).
*   **Almacenamiento:** Sistema de archivos privado abstracto (seguridad contra accesos web directos no autorizados).

## 🚀 Instalación y Despliegue (Local)

Si eres un evaluador técnico de la licitación, puedes ejecutar este prototipo en tu entorno local siguiendo estos pasos:

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/TU-USUARIO/gestion-documental-dms.git
    cd gestion-documental-dms
    ```

2.  **Instalar dependencias del Backend (PHP):**
    ```bash
    composer install
    ```

3.  **Instalar dependencias del Frontend (Node.js):**
    ```bash
    npm install
    npm run build
    ```

4.  **Configurar Entorno:**
    *   Copia el archivo `.env.example` a `.env`.
    *   Configura tus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    *   Genera la llave de la aplicación:
        ```bash
        php artisan key:generate
        ```

5.  **Migrar y Sembrar la Base de Datos:**
    Esto creará las tablas y poblará el sistema con datos de prueba realistas.
    ```bash
    php artisan migrate --seed
    php artisan db:seed --class=DemoDataSeeder
    ```

6.  **Iniciar Servidor Local:**
    ```bash
    php artisan serve
    ```
    Visita `http://localhost:8000` en tu navegador.

## 👤 Credenciales de Demostración

El seeder inicial crea automáticamente una cuenta de Administrador para evaluar el sistema:

*   **Email:** `admin@admin.com`
*   **Contraseña:** `password`

## 🛡️ Seguridad Integrada

Este proyecto está diseñado pensando en la seguridad empresarial desde el día cero:
*   **Protección CSRF** en todos los formularios.
*   **Sanitización Automática** de inputs mediante validadores estrictos.
*   **Protección contra inyección SQL** utilizando el ORM Eloquent.
*   **Archivos Privados:** Los documentos subidos no son accesibles públicamente por URL directa; pasan por un middleware de autorización antes de ser descargados.

---
*Desarrollado para Licitación Corporativa 2026*
