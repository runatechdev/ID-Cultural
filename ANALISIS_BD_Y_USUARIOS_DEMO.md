# 📊 ANÁLISIS DE LA BASE DE DATOS - ID Cultural

## 🔍 Estado Actual de la BD (Generado 20/11/2025)

### Contenedores Docker Activos
```
✅ idcultural_db (MariaDB 10.5)
✅ idcultural_web (PHP 8.2 Apache)
✅ idcultural_pma (PhpMyAdmin - Puerto 8081)
```

### Estadísticas de Datos Actuales
- **Total Artistas**: 5 registros
- **Total Usuarios (Admin)**: 3 registros
- **Total Publicaciones**: 0 registros
- **Total Artistas Famosos**: 10+ registros

---

## 🗄️ Estructura de Tablas Principales

### 1. **Tabla `users`** (Administradores)
**Propósito**: Gestión de roles administrativos
```sql
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,  -- bcrypt hash ($2y$10$...)
  `role` VARCHAR(50) NOT NULL,        -- admin, editor, validador
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
```

**Usuarios Demo Actuales**:
| ID | Nombre | Email | Role |
|---|---|---|---|
| 1 | Administrador Principal | admin@idcultural.com | admin |
| 2 | Editor de Contenidos | editor@idcultural.com | editor |
| 3 | Validador de Artistas | validador@idcultural.com | validador |

**Contraseñas (plaintext)**: Necesarias para acceso demo
- admin: `admin123`
- editor: `editor123`
- validador: `validador123`

---

### 2. **Tabla `artistas`** (Registro de Artistas)
**Propósito**: Perfil de artistas registrados en la plataforma
```sql
CREATE TABLE `artistas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `apellido` VARCHAR(255) NOT NULL,
  `fecha_nacimiento` VARCHAR(20) NOT NULL,
  `genero` VARCHAR(50),
  `pais` VARCHAR(100),
  `provincia` VARCHAR(100),
  `municipio` VARCHAR(100),
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,           -- bcrypt hash
  `role` VARCHAR(50) DEFAULT 'artista',
  `status` VARCHAR(50) DEFAULT 'pendiente',   -- pendiente, validado, rechazado
  `biografia` TEXT,
  `especialidades` VARCHAR(255),
  `instagram` VARCHAR(255),
  `facebook` VARCHAR(255),
  `twitter` VARCHAR(255),
  `sitio_web` VARCHAR(255),
  `foto_perfil` VARCHAR(255),
  `status_perfil` VARCHAR(20) DEFAULT 'pendiente', -- Estado de validación del perfil
  `motivo_rechazo` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB;
```

**Estados Disponibles**:
- `pendiente`: Artista registrado, esperando validación
- `validado`: Perfil aprobado por administrador
- `rechazado`: Perfil rechazado con motivo

---

### 3. **Tabla `publicaciones`** (Obras de Arte)
**Propósito**: Registro de obras, canciones, proyectos artísticos
```sql
CREATE TABLE `publicaciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `artista_id` INT(11) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` LONGTEXT,
  `tipo` VARCHAR(50),                    -- musica, literatura, pintura, etc
  `fecha_creacion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) DEFAULT 'pendiente', -- pendiente, validado, rechazado
  `campos_extra` JSON,                   -- Metadatos dinámicos (JSON)
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `artista_id` (`artista_id`),
  FOREIGN KEY (`artista_id`) REFERENCES `artistas`(`id`)
) ENGINE=InnoDB;
```

---

### 4. **Tabla `artistas_famosos`** (Wiki de Artistas Históricos)
**Propósito**: Base de datos de artistas santiagueños importantes
```sql
CREATE TABLE `artistas_famosos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` VARCHAR(255) NOT NULL,
  `nombre_artistico` VARCHAR(255),
  `fecha_nacimiento` DATE,
  `fecha_fallecimiento` DATE,
  `lugar_nacimiento` VARCHAR(255),
  `municipio` VARCHAR(100) DEFAULT 'Santiago del Estero',
  `provincia` VARCHAR(100) DEFAULT 'Santiago del Estero',
  `pais` VARCHAR(100) DEFAULT 'Argentina',
  `categoria` ENUM('musica','literatura','artes_plasticas','danza','teatro','cine','artesania','folklore'),
  `subcategoria` VARCHAR(100),
  `biografia` TEXT NOT NULL,
  `logros_premios` TEXT,
  `obras_destacadas` TEXT,
  `foto_perfil` VARCHAR(255),
  `foto_galeria` JSON,
  `videos_youtube` JSON,
  `activo` TINYINT(1) DEFAULT 1,
  `destacado` TINYINT(1) DEFAULT 0,
  `orden_visualizacion` INT(11) DEFAULT 0,
  `visitas` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
```

---

### 5. **Tabla `intereses_artista`** (Especialidades)
**Propósito**: Vincular artistas con sus áreas de interés
```sql
CREATE TABLE `intereses_artista` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `artista_id` INT(11),
  `interes` VARCHAR(255),  -- musica, danza, artes_visuales, literatura, etc
  PRIMARY KEY (`id`),
  KEY `artista_id` (`artista_id`)
) ENGINE=InnoDB;
```

---

### Otras Tablas
- `noticias`: Publicaciones de noticias en el sitio
- `system_logs`: Registro de auditoría de acciones administrativas
- `analytics_*`: Datos de visitas, búsquedas y eventos
- `password_reset_tokens`: Recuperación de contraseña
- `preferencias_notificaciones`: Configuración de notificaciones

---

## 🔐 Seguridad de Contraseñas

### Hash Utilizado: bcrypt ($2y$10$)
Las contraseñas se hashean con `password_hash()` de PHP:

```php
// En PHP
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// En SQL (scripts)
-- Crear usuarios con contraseña hasheada
-- La contraseña "demo123" hasheada es:
-- $2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom
```

### Tabla de Contraseñas Demo Recomendadas
```
Usuario: demo_artist_1@demo.com
Pass:    demo_artist_1
Hash:    $2y$10$Xy.GJmBDPXKqKCF3FqIkv.sSuKjKQN1r.gP6p8xZ4zV8tZJ6VqPom
```

---

## 🎯 Estrategia de Carga de Usuarios DEMO

### Fases Recomendadas:

#### **Fase 1: Usuarios Administrativos** (COMPLETADO)
3 usuarios de administración con roles específicos:
- Admin: Acceso total
- Editor: Gestión de contenidos
- Validador: Validación de perfiles

#### **Fase 2: Artistas Demo** (PENDIENTE)
Crear 5-10 artistas de ejemplo con:
- Perfiles completos
- Diferentes especialidades
- Fotos de perfil (placeholder)
- Estados mixtos (algunos validados, otros pendientes)

#### **Fase 3: Obras/Publicaciones Demo** (PENDIENTE)
Agregar 15-20 obras por artista:
- Música, literatura, artes visuales
- Descripciones detalladas
- Metadata (JSON campos_extra)
- Estados variados

#### **Fase 4: Artistas Famosos** (PARCIALMENTE COMPLETADO)
Wiki con 20+ artistas santiagueños históricos

---

## 📋 Datos de Conexión Docker

```php
// config.php
define('DB_HOST', 'db');              // Nombre del contenedor
define('DB_USER', 'runatechdev');
define('DB_PASS', '1234');
define('DB_NAME', 'idcultural');

// Acceso PhpMyAdmin
URL: http://localhost:8081
Usuario: root
Contraseña: root
```

---

## 🚀 Acciones Recomendadas

### ✅ Completado
- [x] Estructura de BD con tablas principales
- [x] Usuarios administrativos (admin, editor, validador)
- [x] Tabla de artistas famosos con datos históricos
- [x] Sistema de roles y permisos

### ⏳ Pendiente
- [ ] Crear script de carga de 10 artistas demo completos
- [ ] Generar 3-5 publicaciones por artista
- [ ] Crear fotos de perfil placeholder
- [ ] Validar integridad de datos
- [ ] Script de reset de BD para demos futuras

### 🔧 Mejoras Futuras
- [ ] Agregar más campos de metadata (JSON)
- [ ] Crear índices para búsquedas frecuentes
- [ ] Implementar soft deletes (eliminación lógica)
- [ ] Auditoría completa de cambios

---

## 📞 Contacto y Acceso

**Plataforma Demo**:
- URL: http://localhost:8080
- Admin: http://localhost:8080/admin

**PhpMyAdmin**:
- URL: http://localhost:8081
- Usuario: root
- Contraseña: root

**Conexión Directa a BD**:
```bash
docker compose exec db mysql -u runatechdev -p1234 idcultural
```

---

**Documento generado**: 20 de noviembre de 2025
**Versión de BD**: MariaDB 10.5.29
**Versión de PHP**: 8.2
