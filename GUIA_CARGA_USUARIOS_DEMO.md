# 📚 GUÍA COMPLETA: CARGA DE USUARIOS DEMO EN ID CULTURAL

**Generado**: 20 de noviembre de 2025  
**Versión**: 1.0  
**Especialista**: Ingeniero en Diseño Web (PHP)

---

## 🎯 Objetivo

Cargar 10 artistas demo con perfiles completos y 26 publicaciones/obras para realizar demostraciones de la plataforma ID Cultural sin datos reales de artistas.

---

## 📊 Qué se Cargará

### **Usuarios Demo**
- **10 Artistas** con perfiles completos
- **Especialidades diversas**: Música, Literatura, Artes Visuales, Danza, Teatro, Fotografía, Artesanía
- **Estados mixtos**: 6 validados, 2 pendientes, 1 rechazado (simula datos reales)
- **Datos completos**: Email, contraseña, biografía, redes sociales

### **Publicaciones/Obras**
- **26 Publicaciones** totales
- **Tipos diversos**: Música, Literatura, Artes Visuales, Danza, Teatro, Fotografía, Artesanía
- **Metadata detallada**: JSON con información específica de cada tipo de obra
- **Estados mixtos**: 14 validadas, 9 pendientes, 1 rechazada

### **Intereses/Especialidades**
- 30+ registros de intereses ligados a artistas
- Categorizados por tipo de arte

---

## 🚀 MÉTODO 1: Carga Automática (RECOMENDADO)

### Paso 1: Verificar que los contenedores estén activos

```bash
cd /home/runatechdev/Documentos/Github/ID-Cultural
docker compose ps
```

**Esperado**: 
```
idcultural_db    mariadb:10.5        Up 17 hours
idcultural_web   id-cultural-web     Up 16 hours
idcultural_pma   phpmyadmin          Up 17 hours
```

### Paso 2: Ejecutar el script PHP de carga

**Opción A: Desde el contenedor web (RECOMENDADO)**
```bash
docker compose exec web php /var/www/app/scripts/cargar_demo.php
```

**Opción B: Desde la terminal del host (si tienes PHP instalado)**
```bash
cd /home/runatechdev/Documentos/Github/ID-Cultural
php scripts/cargar_demo.php
```

### Paso 3: Verificar salida

El script mostrará:
```
✅ Conexión a la base de datos establecida
═══════════════════════════════════════════════════════
  PASO 1: Cargando 10 Artistas Demo...
═══════════════════════════════════════════════════════
✅ 10 artistas demo cargados/verificados

═══════════════════════════════════════════════════════
  PASO 2: Cargando Publicaciones/Obras Demo...
═══════════════════════════════════════════════════════
✅ 26 publicaciones demo cargadas

[... estadísticas ...]

✨ CARGA DE DATOS DEMO COMPLETADA
✅ Total de artistas demo: 10
✅ Total de publicaciones demo: 26

¡Listo para demostrar! 🎉
```

---

## 🔧 MÉTODO 2: Carga Manual via SQL

### Paso 1: Acceder al contenedor de BD

```bash
docker compose exec db bash
```

### Paso 2: Ejecutar scripts SQL secuencialmente

**Cargar artistas y especialidades:**
```bash
mysql -u runatechdev -p1234 idcultural < /docker-entrypoint-initdb.d/../../../database/cargar_usuarios_demo.sql
```

**Cargar publicaciones:**
```bash
mysql -u runatechdev -p1234 idcultural < /docker-entrypoint-initdb.d/../../../database/cargar_publicaciones_demo.sql
```

### Paso 3: Verificar datos

```bash
mysql -u runatechdev -p1234 idcultural -e "SELECT COUNT(*) as artistas FROM artistas WHERE id BETWEEN 10 AND 19; SELECT COUNT(*) as publicaciones FROM publicaciones WHERE artista_id BETWEEN 10 AND 19;"
```

---

## 🌐 MÉTODO 3: Carga via PhpMyAdmin

### Paso 1: Acceder a PhpMyAdmin

```
URL: http://localhost:8081
Usuario: root
Contraseña: root
```

### Paso 2: Seleccionar base de datos `idcultural`

### Paso 3: Ir a la pestaña "SQL"

### Paso 4: Copiar y pegar el contenido de los archivos

**Primero ejecutar**: `/database/cargar_usuarios_demo.sql`
**Luego ejecutar**: `/database/cargar_publicaciones_demo.sql`

### Paso 5: Click en "Ejecutar" en ambos casos

---

## 🔐 Credenciales de Acceso

### Usuarios Administrativos (Preexistentes)

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@idcultural.com | admin123 | Administrador |
| editor@idcultural.com | editor123 | Editor |
| validador@idcultural.com | validador123 | Validador |

### Artistas Demo (Nuevos)

Todos usan contraseña: `demo123` o `demo456` (alternado)

| # | Nombre | Email | Especialidad | Estado |
|---|--------|-------|--------------|--------|
| 1 | Juan Reyes | juan.reyes.demo@demo.com | Música Folk | Validado |
| 2 | María Fernández | maria.fernandez.demo@demo.com | Literatura | Validado |
| 3 | Carlos Méndez | carlos.mendez.demo@demo.com | Artes Visuales | Pendiente |
| 4 | Ana González | ana.gonzalez.demo@demo.com | Danza | Validado |
| 5 | Lucas Silva | lucas.silva.demo@demo.com | Música Electrónica | Rechazado |
| 6 | Rosario Díaz | rosario.diaz.demo@demo.com | Artesanía | Validado |
| 7 | Miguel Torres | miguel.torres.demo@demo.com | Fotografía | Pendiente |
| 8 | Federico López | federico.lopez.demo@demo.com | Teatro | Validado |
| 9 | Isabella Ruiz | isabella.ruiz.demo@demo.com | Música Clásica | Validado |
| 10 | Roberto Navarro | roberto.navarro.demo@demo.com | Escultura | Validado |

---

## 📋 Estructura de Datos Cargados

### Artistas Demo por Estado

```
✅ Validados (6):     Juan, María, Ana, Rosario, Federico, Isabella, Roberto
⏳ Pendientes (2):    Carlos, Miguel
❌ Rechazados (1):    Lucas
```

### Publicaciones por Estado

```
✅ Validadas (14):    Obras de artistas confirmados
⏳ Pendientes (9):    Obras en espera de validación
❌ Rechazadas (1):    Obras que no cumplen requisitos
```

### Publicaciones por Tipo

```
Música:          6 obras
Artes Visuales:  5 obras
Literatura:      3 obras
Teatro:          3 obras
Danza:           3 obras
Fotografía:      2 obras
Artesanía:       2 obras
Música Clásica:  3 obras
Escultura:       3 obras
```

---

## 🧪 Verificación de Datos

### Verificar que los datos se cargaron correctamente

**En terminal SQL (Docker):**
```bash
docker compose exec db mysql -u runatechdev -p1234 idcultural
```

```sql
-- Ver artistas demo
SELECT id, CONCAT(nombre, ' ', apellido) as nombre, email, status_perfil 
FROM artistas 
WHERE id BETWEEN 10 AND 19 
ORDER BY id;

-- Ver publicaciones por artista
SELECT 
    a.id, 
    CONCAT(a.nombre, ' ', a.apellido) as artista,
    COUNT(p.id) as numero_obras,
    GROUP_CONCAT(DISTINCT p.status) as estados
FROM artistas a
LEFT JOIN publicaciones p ON a.id = p.artista_id
WHERE a.id BETWEEN 10 AND 19
GROUP BY a.id;

-- Contar totales
SELECT 
    (SELECT COUNT(*) FROM artistas WHERE id BETWEEN 10 AND 19) as total_artistas,
    (SELECT COUNT(*) FROM publicaciones WHERE artista_id BETWEEN 10 AND 19) as total_publicaciones,
    (SELECT COUNT(*) FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19) as total_intereses;
```

---

## 🧹 Eliminar Datos Demo (Si es necesario)

Si necesitas limpiar los datos demo para recargar o probar nuevamente:

### Método 1: Script SQL

```sql
-- Eliminar intereses
DELETE FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19;

-- Eliminar publicaciones
DELETE FROM publicaciones WHERE artista_id BETWEEN 10 AND 19;

-- Eliminar artistas
DELETE FROM artistas WHERE id BETWEEN 10 AND 19;

-- Verificar que se eliminó todo
SELECT COUNT(*) as artistas_demo FROM artistas WHERE id BETWEEN 10 AND 19;
```

### Método 2: Desde Docker

```bash
docker compose exec db mysql -u runatechdev -p1234 idcultural -e \
"DELETE FROM intereses_artista WHERE artista_id BETWEEN 10 AND 19; \
DELETE FROM publicaciones WHERE artista_id BETWEEN 10 AND 19; \
DELETE FROM artistas WHERE id BETWEEN 10 AND 19;"
```

---

## 🎮 Pruebas Sugeridas Después de Carga

### 1. Acceder como Administrador
```
URL: http://localhost:8080
Email: admin@idcultural.com
Password: admin123
```

### 2. Ver Dashboard
- Verificar cantidad de artistas
- Verificar cantidad de publicaciones pendientes de validación

### 3. Validar un Artista Demo
- Ir a gestión de artistas
- Seleccionar "Carlos Méndez" (pendiente)
- Aprobar o rechazar su perfil

### 4. Acceder como Artista Demo
```
Email: juan.reyes.demo@demo.com
Password: demo123
```
- Ver su perfil
- Ver sus publicaciones
- Crear una nueva publicación

### 5. Verificar Búsqueda
- Buscar "Música" → debe mostrar obras de Juan, Lucas, Isabella
- Buscar "Danza" → debe mostrar obras de Ana González
- Buscar "Fotografía" → debe mostrar obras de Miguel Torres

---

## 📞 Contacto y Soporte

### Archivos Principales
- **Análisis BD**: `ANALISIS_BD_Y_USUARIOS_DEMO.md`
- **Script Artistas**: `/database/cargar_usuarios_demo.sql`
- **Script Publicaciones**: `/database/cargar_publicaciones_demo.sql`
- **Script PHP Automático**: `/scripts/cargar_demo.php`

### URLs de Acceso
- **Plataforma Demo**: http://localhost:8080
- **PhpMyAdmin**: http://localhost:8081
- **API Docs**: http://localhost:8080/api-docs.html (si existe)

### Comandos Útiles

```bash
# Ver logs de web
docker compose logs web -f

# Ver logs de BD
docker compose logs db -f

# Acceso directo a BD
docker compose exec db mysql -u runatechdev -p1234 idcultural

# Ejecutar script PHP
docker compose exec web php /var/www/app/scripts/cargar_demo.php

# Reiniciar servicios
docker compose restart

# Detener servicios
docker compose down

# Iniciar servicios
docker compose up -d
```

---

## ✅ Checklist de Verificación

- [ ] Docker compose está activo (`docker compose ps`)
- [ ] Se ejecutó el script de carga sin errores
- [ ] Pueden acceder usuarios admin con credenciales
- [ ] Se ven 10 artistas nuevos en la BD
- [ ] Se ven 26 publicaciones nuevas
- [ ] Pueden acceder artistas demo con emails de demo.com
- [ ] La búsqueda filtra por especialidad correctamente
- [ ] PhpMyAdmin muestra los datos correctamente

---

## 📈 Próximos Pasos Sugeridos

1. **Crear más datos demo**:
   - Agregar comentarios/reviews de publicaciones
   - Crear favoritos/likes
   - Simular interacciones de usuarios

2. **Optimizar BD**:
   - Crear índices en búsquedas frecuentes
   - Verificar integridad referencial

3. **Documentación**:
   - Actualizar documentación de API
   - Crear ejemplos de cURL
   - Documentar eventos de sistema

4. **Testing**:
   - Tests unitarios de artistas
   - Tests de integridad de publicaciones
   - Tests de búsqueda y filtros

---

**Documento Generado**: 20 de noviembre de 2025  
**Ingeniero Web Especialista en PHP**: GitHub Copilot  
**Estado**: ✅ Listo para producción de demos
