# 🎭 ID Cultural

Proyecto desarrollado para la Subsecretaría de Cultura de Santiago del Estero como parte de las Prácticas Profesionalizantes del ITSE.

---

## 📚 Descripción

**ID Cultural** es una plataforma web tipo "Wikipedia local", destinada a **centralizar, validar y exhibir** información sobre artistas y expresiones culturales de Santiago del Estero. El sistema permite a los artistas **crear y gestionar borradores de perfiles culturales**, que luego son sometidos a un proceso de **validación por parte de moderadores**. Una vez aprobados, estos perfiles se publican en una **Wiki de Artistas** abierta al público, conformando una valiosa biblioteca digital de contenido artístico local.

---

## 🗂️ Estructura del Proyecto
```
ID_Cultural/
│
├── src/
│ ├── controllers/ # Lógica del sistema y gestión de rutas
│ ├── models/ # Representación de datos y lógica de interacción con la DB
│ └── views/ # Interfaz HTML
│ ├── components/ # Navbar, footer, etc.
│ └── pages/
│ ├── public/ # Inicio, búsqueda, Wiki de Artistas
│ ├── auth/ # Login, registro
│ ├── user/ # Panel de artistas (creación y gestión de borradores)
│ └── admin/ # Administración de usuarios, validaciones y gestión general
│
├── static/
│ ├── css/
│ │ ├── main.css # Estilos generales
│ │ ├── login.css # Estilos por página
│ │ ├── admin.css
│ │ └── wiki.css
│ ├── js/
│ │ ├── login.js
│ │ └── admin.js
│ └── img/
│ └── logo.png
│
├── database/
│ ├── dump_idcultural.sql # Esquema de la base de datos y datos de ejemplo
│
├── config/
│ ├── db.php # Configuración de conexión a base de datos
│ └── rutas.php # Definición de rutas del sistema
│
├── tests/
│ ├── test-usuarios.js # Tests para la gestión de usuarios
│ └── test-artistas.js # Tests para la gestión de artistas y sus publicaciones
│
└── docs/
├── manual-usuario.pdf # Guía para usuarios finales
└── informe-tecnico.docx # Documentación técnica del proyecto

---

## ⚙️ Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Base de Datos:** MySQL/MariaDB
- **Contenedores:** Docker, Docker Compose (para orquestación del entorno de desarrollo)

---

## ✅ Funcionalidades Clave

- **Registro y Autenticación:** Sistema robusto para artistas, validadores, editores y administradores.
- **Gestión de Perfiles por Artistas:**
    - Creación y edición de **borradores** de perfiles culturales.
    - Envío de borradores a **validación**.
    - Visualización del **estado** de sus envíos (borrador, pendiente, validado, rechazado).
- **Proceso de Validación y Moderación:**
    - Panel específico para **validadores** para revisar y aprobar/rechazar perfiles pendientes.
    - Panel para **editores** con capacidad de gestionar y modificar cualquier perfil.
- **Wiki de Artistas Pública:** Exhibición de perfiles culturales **validados**, con opciones de búsqueda y filtrado.
- **Carga de Contenido Multimedia:** Soporte para incluir obras, eventos, biografías, documentos y otros materiales asociados a los artistas.
- **Buscador Avanzado:** Filtros por género, localidad, tipo de expresión artística y año.
- **Panel Administrativo:** Gestión completa de usuarios (artistas, validadores, editores, administradores) y contenidos.
```
---

## 👥 Equipo de Desarrollo

**Runatech** – Estudiantes del ITSE Santiago del Estero

- Maximiliano Fabián Padilla
- Marcos Ariel Romano
- Mario Sebastián Ruiz
- Sandra Soledad Sánchez

Colaboración: Subsecretaría de Cultura de Santiago del Estero

---

## 📄 Licencia

Este proyecto fue realizado con fines educativos y de contribución cultural. Derechos reservados al equipo **Runatech** y a la **Subsecretaría de Cultura de Santiago del Estero**.
