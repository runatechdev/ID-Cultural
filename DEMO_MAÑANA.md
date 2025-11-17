# 🚀 DEMO MAÑANA - INSTRUCCIONES RÁPIDAS

## Estado Actual ✅
- ✅ Docker corriendo (Web + BD + PhpMyAdmin)
- ✅ Aplicación en http://localhost:8080
- ✅ API funcionando
- ✅ ngrok instalado y listo
- ✅ Todos los cambios implementados

## 3 Pasos para Hacer la Demo

### Paso 1: Ejecutar el checklist (2 minutos)
```bash
cd /home/runatechdev/Documentos/Github/ID-Cultural
bash pre-demo-check.sh
```

Debería salir todo verde ✅

### Paso 2: Iniciar ngrok (1 minuto)
```bash
bash deploy-ngrok.sh
```

Verás algo así:
```
Forwarding    https://1234-5678-9abc.ngrok.io -> http://localhost:8080
```

**Esa es tu URL de demo pública** 🎉

### Paso 3: Compartir y mostrar (tiempo restante)
- Copia la URL
- Abrela en navegador
- Muestra las funcionalidades

## 📱 URLs para la Demo

| Página | URL |
|--------|-----|
| Home | `https://xxxx-xxxx-xxxx.ngrok.io/` |
| Wiki | `https://xxxx-xxxx-xxxx.ngrok.io/wiki.php` |
| Panel Editor | `https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/panel_editor.php` |
| Gestión Artistas | `https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/gestion_artistas_famosos.php` |
| Editar Inicio | `https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/gestion_inicio.php` |
| PhpMyAdmin | `http://localhost:8081` (local para administración) |

## 🎯 Funcionalidades a Demostrar

### 1. Gestión de Artistas Famosos ⭐
- ✅ Agregar artista sin campo emoji (se asigna automáticamente)
- ✅ Tabla muestra categoría con emoji, especialidad y reconocimiento
- ✅ Editar artista existente
- ✅ Los cambios se reflejan en Wiki automáticamente

### 2. Vista de Artistas en Wiki
- ✅ Carga artistas desde BD
- ✅ Emojis automáticos por categoría
- ✅ Cards de artistas con biografía
- ✅ Filtros funcionando

### 3. Editor de Página Principal
- ✅ Fondo limpio sin patrones oscuros
- ✅ Editable con Quill.js
- ✅ Se ve perfectamente para escribir

## 🔐 Credenciales para Login

**Editor:**
- Usuario: `editor@test.com`
- Contraseña: `password123`

**Admin:**
- Usuario: `admin@test.com`
- Contraseña: `admin123`

## ⚠️ Notas Importantes

1. **Cada vez que reinicies ngrok**, obtendrás una URL diferente
2. **La sesión tiene límites de tiempo** (depende del plan de ngrok)
3. **Es HTTPS automáticamente** (aceptar advertencia si sale)
4. **Los datos están en la BD** (persistent entre reinicios)

## 🐛 Si Algo Falla

### Opción 1: Reiniciar ngrok
```bash
# Termina el proceso (Ctrl+C en la terminal)
# Luego ejecuta de nuevo:
bash deploy-ngrok.sh
```

### Opción 2: Verificar localhost primero
```bash
curl http://localhost:8080
```

### Opción 3: Revisar logs de Docker
```bash
docker-compose logs -f web
docker-compose logs -f db
```

## 📋 Checklist Final (5 minutos antes)

- [ ] Ejecuté `bash pre-demo-check.sh` ✅
- [ ] ngrok está corriendo con URL visible
- [ ] Puedo acceder a la URL en navegador
- [ ] Login funciona con credenciales
- [ ] Panel de Editor carga
- [ ] Tabla de artistas muestra datos
- [ ] Wiki muestra artistas con emojis
- [ ] Tengo la URL copiada para compartir

## 💡 Tips Pro para Demo

1. **Abre en incógnito** para evitar cache
2. **Prueba el login primero** en la URL pública
3. **Muestra la BD** en PhpMyAdmin (local)
4. **Explica el flujo** editor → BD → Wiki
5. **Ten videos/screenshots** de respaldo

## 🎉 ¡Listo!

Tu aplicación estará disponible públicamente mañana. Solo ejecuta los 2 comandos en orden y compartir la URL.

```bash
bash pre-demo-check.sh
bash deploy-ngrok.sh
```

¡Buena suerte! 🚀
