# 🔄 GUÍA DE ACTUALIZACIÓN DE ARCHIVOS JAVASCRIPT

## 📝 Archivos JS que NECESITAN actualización

Los siguientes archivos en `/public/static/js/` usan las APIs antiguas y necesitan actualizar sus llamadas:

### 1. **registro.js** - Formulario de registro
```javascript
❌ Antiguo: fetch(`${BASE_URL}api/register_artista.php`, ...)

✅ Nuevo:
const formData = new FormData();
formData.append('action', 'register');
// ... otros campos
fetch(`${BASE_URL}api/artistas.php`, { method: 'POST', body: formData })
```

---

### 2. **ver_borradores.js** - Ver borradores del artista
```javascript
❌ Antiguo: 
fetch(`${BASE_URL}api/get_mis_borradores.php`)
fetch(`${BASE_URL}api/delete_publicacion.php`, ...)

✅ Nuevo:
fetch(`${BASE_URL}api/borradores.php?action=get`)
formData.append('action', 'delete');
fetch(`${BASE_URL}api/borradores.php`, { method: 'POST', body: formData })
```

---

### 3. **crear-borrador.js** - Crear/editar borradores
```javascript
❌ Antiguo:
fetch(`${BASE_URL}api/save_borrador.php`, ...)

✅ Nuevo:
const formData = new FormData();
formData.append('action', 'save');
// ... otros campos
fetch(`${BASE_URL}api/borradores.php`, { method: 'POST', body: formData })
```

---

### 4. **solicitudes_enviadas.js** - Ver solicitudes de validación
```javascript
❌ Antiguo:
fetch(`${BASE_URL}api/get_mis_solicitudes.php`)

✅ Nuevo:
fetch(`${BASE_URL}api/solicitudes.php?action=get_my`)
```

---

### 5. **panel_editor.js** - Panel de edición de noticias
```javascript
❌ Antiguo:
fetch(`${BASE_URL}api/get_noticias.php?limit=100`)
fetch(`${BASE_URL}api/add_noticia.php`, ...)
fetch(`${BASE_URL}api/get_noticia_detalle.php?id=${id}`)
fetch(`${BASE_URL}api/edit_noticia.php`, ...)
fetch(`${BASE_URL}api/delete_noticia.php`, ...)

✅ Nuevo:
fetch(`${BASE_URL}api/noticias.php?action=get`)
formData.append('action', 'add');
fetch(`${BASE_URL}api/noticias.php`, { method: 'POST', body: formData })
fetch(`${BASE_URL}api/noticias.php?action=get&id=${id}`)
formData.append('action', 'update');
fetch(`${BASE_URL}api/noticias.php`, { method: 'POST', body: formData })
formData.append('action', 'delete');
fetch(`${BASE_URL}api/noticias.php`, { method: 'POST', body: formData })
```

---

### 6. **index.js** - Página de inicio
```javascript
❌ Antiguo:
fetch(`${BASE_URL}api/get_noticias.php?limit=6`)
fetch(`${BASE_URL}api/get_noticia_detalle.php?id=${noticiaId}`)

✅ Nuevo:
fetch(`${BASE_URL}api/noticias.php?action=get`) // Agregar límite de otra forma si es necesario
fetch(`${BASE_URL}api/noticias.php?action=get&id=${noticiaId}`)
```

---

### 7. **gestion_pendientes.js** - Gestión de obras pendientes (panel validador)
```javascript
❌ Antiguo:
fetch(`${BASE_URL}api/get_publicaciones.php?estado=pendiente`)

✅ Nuevo: (SIN CAMBIO - esta API fue corregida, no se cambió de nombre)
fetch(`${BASE_URL}api/get_publicaciones.php?estado=pendiente`)
```

---

### 8. **panel_validador.js** - Estadísticas del validador
✅ **SIN CAMBIOS NECESARIOS** - Esta API se mantiene igual:
```javascript
fetch(`${BASE_URL}api/get_estadisticas_validador.php`)
```

---

### 9. **log_sistema.js** - Logs del sistema
✅ **SIN CAMBIOS NECESARIOS** - Esta API se mantiene igual:
```javascript
fetch(`${BASE_URL}api/get_logs.php`)
```

---

## 📊 TABLA DE MIGRACIONES

| Archivo JS | APIs Antiguas | Nuevas APIs | Estado |
|------------|---------------|------------|--------|
| registro.js | register_artista.php | artistas.php?action=register | ⏳ TODO |
| ver_borradores.js | get_mis_borradores.php, delete_publicacion.php | borradores.php | ⏳ TODO |
| crear-borrador.js | save_borrador.php | borradores.php?action=save | ⏳ TODO |
| solicitudes_enviadas.js | get_mis_solicitudes.php | solicitudes.php?action=get_my | ⏳ TODO |
| panel_editor.js | get_noticias.php, add_noticia.php, etc. | noticias.php | ⏳ TODO |
| index.js | get_noticias.php, get_noticia_detalle.php | noticias.php | ⏳ TODO |
| gestion_pendientes.js | - | - | ✅ OK |
| panel_validador.js | - | - | ✅ OK |
| log_sistema.js | - | - | ✅ OK |

---

## 🔍 BÚSQUEDA RÁPIDA

Para encontrar todas las referencias a APIs antiguas en JavaScript:

```bash
# Buscar en todos los archivos JS
grep -r "api/get_" /home/runatechdev/Documentos/Github/ID-Cultural/public/static/js/
grep -r "api/add_" /home/runatechdev/Documentos/Github/ID-Cultural/public/static/js/
grep -r "api/delete_" /home/runatechdev/Documentos/Github/ID-Cultural/public/static/js/
grep -r "api/update_" /home/runatechdev/Documentos/Github/ID-Cultural/public/static/js/
grep -r "api/save_" /home/runatechdev/Documentos/Github/ID-Cultural/public/static/js/
```

---

## 📋 PATRÓN GENERAL DE MIGRACIÓN

### Patrón Antiguo (Endpoints separados):
```javascript
fetch('api/get_artistas.php')          // Obtener
fetch('api/add_artistas.php')          // Crear
fetch('api/update_artistas.php')       // Actualizar
fetch('api/delete_artistas.php')       // Eliminar
```

### Patrón Nuevo (CRUD Unificado):
```javascript
// Obtener
fetch('api/artistas.php?action=get')

// Crear
const formData = new FormData();
formData.append('action', 'add');
formData.append('nombre', 'Juan');
fetch('api/artistas.php', { method: 'POST', body: formData })

// Actualizar
const formData = new FormData();
formData.append('action', 'update');
formData.append('id', '1');
fetch('api/artistas.php', { method: 'POST', body: formData })

// Eliminar
const formData = new FormData();
formData.append('action', 'delete');
formData.append('id', '1');
fetch('api/artistas.php', { method: 'POST', body: formData })
```

---

## ✅ CHECKLIST DE ACTUALIZACIÓN

- [ ] Actualizar `registro.js`
- [ ] Actualizar `ver_borradores.js`
- [ ] Actualizar `crear-borrador.js`
- [ ] Actualizar `solicitudes_enviadas.js`
- [ ] Actualizar `panel_editor.js` (PRIORITARIO - múltiples cambios)
- [ ] Actualizar `index.js`
- [ ] Probar registro de artista
- [ ] Probar crear borradores
- [ ] Probar ver borradores
- [ ] Probar enviar a validación
- [ ] Probar crear noticias
- [ ] Probar validar obras
- [ ] Confirmar que todas las funcionalidades funcionan

---

**Fecha:** 4 de Noviembre de 2025  
**Estado:** Listo para actualizar
