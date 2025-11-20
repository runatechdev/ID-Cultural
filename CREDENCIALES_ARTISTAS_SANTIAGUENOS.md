# ARTISTAS SANTIAGUEÑOS FAMOSOS - CREDENCIALES

## 📋 Listado de Artistas

Todos los artistas tienen la misma contraseña: **clave123**

| # | Nombre | Email | Imagen Perfil | Estado |
|---|--------|-------|---------------|--------|
| 1 | Ramona Galarza | ramona.galarza@idcultural.com | `ramona.jpg` | Validado |
| 2 | Jorge Rojas | jorge.rojas@idcultural.com | `jorge.jpg` | Validado |
| 3 | Ricardo Suárez | ricardo.suarez@idcultural.com | `ricardo.jpg` | Validado |
| 4 | Peteco Carabajal | peteco.carabajal@idcultural.com | `peteco.jpg` | Validado |
| 5 | Jacqueline Carabajal | jacqueline.carabajal@idcultural.com | `jacqueline.jpg` | Validado |
| 6 | Raly Barrionuevo | raly.barrionuevo@idcultural.com | `raly.jpg` | Validado |
| 7 | Roxana Carabajal | roxana.carabajal@idcultural.com | `roxana.jpg` | Validado |
| 8 | Luciano Carabajal | luciano.carabajal@idcultural.com | `luciano.jpg` | Validado |
| 9 | Ale Brizuela | ale.brizuela@idcultural.com | `ale.jpg` | Validado |
| 10 | Dúo Coplanacu | duo.coplanacu@idcultural.com | `coplanacu.jpg` | Validado |

## 🎨 Imágenes Requeridas

### Fotos de Perfil (10 archivos)
Ubicación: `/public/uploads/imagenes/`

- `ramona.jpg`
- `jorge.jpg`
- `ricardo.jpg`
- `peteco.jpg`
- `jacqueline.jpg`
- `raly.jpg`
- `roxana.jpg`
- `luciano.jpg`
- `ale.jpg`
- `coplanacu.jpg`

### Imágenes de Obras (40 archivos)
Ubicación: `/public/uploads/imagenes/`

- `obra1.jpg` a `obra40.jpg` (numeradas secuencialmente)

**Total de imágenes necesarias: 50 archivos**

## 📁 Ubicación Completa

```
/home/runatechdev/Documentos/Github/ID-Cultural/public/uploads/imagenes/
```

## 📊 Estadísticas

- **Total Artistas:** 10
- **Total Obras:** 40
- **Obras Validadas:** 10 (una por artista)
- **Obras Pendientes:** 30

## 🎵 Distribución de Obras por Artista

Cada artista tiene 4 obras:
- 1 obra validada (visible en el wiki)
- 3 obras pendientes de validación

| Artista | Obra Validada | Obras Pendientes |
|---------|---------------|------------------|
| Ramona Galarza | La Telesita | obra2, obra3, obra4 |
| Jorge Rojas | Ladrando a la Luna | obra6, obra7, obra8 |
| Ricardo Suárez | Solo de Guitarra | obra10, obra11, obra12 |
| Peteco Carabajal | Chacarera de un Triste | obra14, obra15, obra16 |
| Jacqueline Carabajal | Zamba de mi Esperanza | obra18, obra19, obra20 |
| Raly Barrionuevo | Zamba del Emigrante | obra22, obra23, obra24 |
| Roxana Carabajal | Percusión y Voz | obra26, obra27, obra28 |
| Luciano Carabajal | Guitarra Santiagueña | obra30, obra31, obra32 |
| Ale Brizuela | Chacarera del Violín | obra34, obra35, obra36 |
| Dúo Coplanacu | Chacarera del Rancho | obra38, obra39, obra40 |

## 🔐 Información de Acceso

**Contraseña (para todos):** clave123  
**Hash bcrypt:** `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`

## ✅ Pasos para Completar la Carga

1. **Descargar/preparar 50 imágenes**
   - 10 fotos de perfil con los nombres de los artistas
   - 40 imágenes de obras numeradas del 1 al 40

2. **Copiar las imágenes a la carpeta**
   ```bash
   cp *.jpg /home/runatechdev/Documentos/Github/ID-Cultural/public/uploads/imagenes/
   ```

3. **Verificar permisos**
   ```bash
   cd /home/runatechdev/Documentos/Github/ID-Cultural
   docker exec idcultural_web chmod -R 777 /var/www/app/public/uploads
   ```

4. **Importar los datos**
   ```bash
   cd /home/runatechdev/Documentos/Github/ID-Cultural
   docker exec -i idcultural_db mysql -u runatechdev -p1234 idcultural < database/artistas_santiaguenos_famosos.sql
   ```

5. **Verificar la carga**
   ```bash
   docker exec idcultural_db mysql -u runatechdev -p1234 idcultural -e "
   SELECT COUNT(*) as artistas FROM artistas;
   SELECT COUNT(*) as obras FROM publicaciones;
   SELECT estado, COUNT(*) FROM publicaciones GROUP BY estado;
   "
   ```

## 🌐 Acceso a la Plataforma

**URL:** https://60bc1c5ec999.ngrok-free.app/

**Roles disponibles:**
- **Artistas:** Los 10 emails listados arriba
- **Validador:** validador@idcultural.com (si existe en la BD)
- **Admin:** admin@idcultural.com (si existe en la BD)

## 📝 Notas Importantes

- Todas las imágenes deben estar en formato JPG
- Los nombres de archivo distinguen mayúsculas/minúsculas (usar minúsculas)
- Las biografías de los artistas están basadas en sus trayectorias reales
- Todos los artistas son de Santiago del Estero, Argentina
- La familia Carabajal tiene varios miembros representados (Peteco, Jacqueline, Roxana, Luciano)
