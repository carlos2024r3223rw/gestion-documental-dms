# 📁 Gestión Documental DMS — Sistema de Control de Documentos Empresariales

> Sistema corporativo para la administración, control de versiones y auditoría de documentos sensibles. Diseñado para empresas que necesitan trazabilidad y seguridad en su gestión documental.

[![Demo en vivo](https://img.shields.io/badge/Demo-Live-brightgreen?style=for-the-badge)](https://gestion-documental-dms-production.up.railway.app/dashboard)
[![Stack](https://img.shields.io/badge/Stack-PHP%20%7C%20MySQL%20%7C%20Railway-purple?style=for-the-badge&logo=php)](https://php.net)

---

## 🔴 El Problema

La empresa manejaba documentos sensibles sin ningún sistema de control:

- ❌ Documentos críticos guardados en carpetas compartidas sin versionado
- ❌ Sin auditoría de quién accedió o modificó cada documento
- ❌ Riesgo de pérdida de información o accesos no autorizados
- ❌ Sin distinción entre permisos de staff y administración

## ✅ La Solución

Sistema DMS completo con control de acceso por roles y trazabilidad total:

- 🔐 **RBAC** (Role-Based Access Control) — Admin y Staff con permisos diferenciados
- 📂 **Control de versiones** — historial completo de cambios por documento
- 🔍 **Búsqueda avanzada** — filtrado por categoría, fecha, autor y estado
- 📋 **Auditoría completa** — log de accesos, modificaciones y descargas
- ☁️ **Desplegado en Railway** con CI/CD automático

## 🧠 Reto Técnico Resuelto

El principal reto fue diseñar el **sistema de permisos RBAC** en PHP puro sin framework, garantizando que un usuario Staff no pueda acceder a documentos confidenciales aunque conozca la URL directa. Se implementó verificación de sesión + validación de permisos por middleware en cada endpoint sensible.

---

## 🛠️ Stack Tecnológico

| Área | Tecnología |
|---|---|
| Backend | PHP 8.x (sin framework) |
| Base de datos | MySQL con relaciones normalizadas |
| Hosting | Railway (cloud PaaS) |
| Auth | Sistema de sesiones PHP + bcrypt |
| Frontend | Blade templates, CSS, JavaScript vanilla |

---

## 🚀 Instalación Local

```bash
git clone https://github.com/carlos2024r3223rw/gestion-documental-dms.git
cd gestion-documental-dms

# Configurar la base de datos
# 1. Crear una base de datos MySQL
# 2. Importar el esquema
mysql -u root -p nombre_db < database/schema.sql

# Configurar las variables de entorno
cp .env.example .env
# Editar .env con tus credenciales de DB
```

---

## 🔑 Acceso Demo (Producción)

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `jefe@admin.com` | `password123` |
| Staff | `user@admin.com` | `password123` |

🔗 **Demo:** https://gestion-documental-dms-production.up.railway.app/dashboard

---

## 📁 Estructura del Proyecto

```
gestion-documental-dms/
├── app/
│   ├── controllers/       # Lógica de negocio (DocumentController, AuthController)
│   ├── models/            # Modelos de datos (Document, User, AuditLog)
│   ├── middleware/        # Verificación de sesión y permisos RBAC
│   └── views/             # Templates Blade
├── database/
│   └── schema.sql         # Estructura completa de la BD
├── public/                # Entry point, assets CSS/JS
└── config/                # Configuración de BD y constantes
```

---

## 👤 Autor

**Carlos Manuel Martínez Lima** — Full Stack Developer · Especialista SaaS & eCommerce

[![Portfolio](https://img.shields.io/badge/Portfolio-webcarlos--jet.vercel.app-blue?style=flat-square)](https://webcarlos-jet.vercel.app)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-0077B5?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/carlos-manuel-martinez-lima-ba238a1a9/)
[![Email](https://img.shields.io/badge/Email-cm7887575%40gmail.com-red?style=flat-square&logo=gmail)](mailto:cm7887575@gmail.com)
