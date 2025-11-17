# 📦 RESUMEN - DESPLIEGUE NGROK PARA DEMO

## ¿Por qué ngrok?
✅ Expone tu localhost a internet sin servidor externo
✅ URL pública (https) automática
✅ Perfecto para demos, testing, presentations
✅ No requiere configuración compleja
✅ Funciona en cualquier dispositivo/navegador

## Archivos Creados

| Archivo | Propósito |
|---------|-----------|
| `deploy-ngrok.sh` | Script principal para iniciar ngrok |
| `deploy-ngrok-background.sh` | Para dejar corriendo en background |
| `pre-demo-check.sh` | Verificar todo antes de demo |
| `DEPLOY_NGROK.md` | Guía completa y detallada |
| `DEMO_MAÑANA.md` | Instrucciones rápidas para mañana |

## 🚀 Comando Más Rápido (COPY-PASTE)

```bash
cd /home/runatechdev/Documentos/Github/ID-Cultural && bash deploy-ngrok.sh
```

Eso es todo. ngrok se iniciará y verás:
```
Forwarding    https://xxxx-xxxx-xxxx.ngrok.io -> http://localhost:8080
```

## 📱 Opciones de Ejecución

### Opción 1: Interactivo (RECOMENDADO para mañana)
```bash
bash deploy-ngrok.sh
```
- Verás todo en pantalla
- Puedes terminar fácil con Ctrl+C
- Perfecto para una demo

### Opción 2: Background (si dejas corriendo)
```bash
nohup bash deploy-ngrok-background.sh > ngrok-demo.log 2>&1 &
```
- La app sigue corriendo si cierras terminal
- Los logs van a `ngrok-demo.log`
- Para ver URL: `grep "PÚBLICA" ngrok-demo.log`

### Opción 3: Tmux/Screen (para sessions persistentes)
```bash
tmux new-session -d -s demo "bash /home/runatechdev/Documentos/Github/ID-Cultural/deploy-ngrok.sh"
```

## ✅ Pre-Demo Checklist (EJECUTAR PRIMERO)

```bash
bash pre-demo-check.sh
```

Resultado esperado:
```
✅ Docker instalado
✅ Contenedores están corriendo
✅ Aplicación responde en localhost:8080
✅ ngrok instalado
✅ Base de datos conectada
✅ config.php existe
✅ API de artistas responde
✅ LISTO PARA DEMO
```

## 🔐 Credenciales Demo

```
📧 Editor
User: editor@test.com
Pass: password123

📧 Admin
User: admin@test.com
Pass: admin123
```

## 📍 URLs Principales en la Demo

```
Home:                https://xxxx-xxxx-xxxx.ngrok.io/
Wiki:                https://xxxx-xxxx-xxxx.ngrok.io/wiki.php
Panel Editor:        https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/panel_editor.php
Artistas Famosos:    https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/gestion_artistas_famosos.php
Editar Inicio:       https://xxxx-xxxx-xxxx.ngrok.io/src/views/pages/editor/gestion_inicio.php
PhpMyAdmin (local):  http://localhost:8081
```

## 🎯 Flow a Demostrar Mañana

1. **Login como Editor**
   ```
   Ir a: /src/views/pages/auth/login.php
   Usuario: editor@test.com
   Contraseña: password123
   ```

2. **Gestionar Artistas Famosos**
   ```
   Panel → Gestión Artistas → Ver tabla actual
   ```

3. **Agregar nuevo artista**
   ```
   Botón "Agregar Artista Famoso"
   - Nombre completo
   - Seleccionar categoría (emoji automático)
   - Especialidad
   - Biografía
   - Tipo de reconocimiento
   - Logros (opcional)
   ```

4. **Ver en Wiki**
   ```
   Ir a: /wiki.php → Tab Artistas Famosos
   Mostrar que se actualiza automáticamente
   ```

5. **Editor de Inicio**
   ```
   Panel → Editar Página Principal
   Mostrar fondo limpio para escribir
   ```

## ⚠️ Problemas Comunes

| Problema | Solución |
|----------|----------|
| "localhost:8080 no responde" | `docker-compose up -d` en la carpeta del proyecto |
| "ngrok no inicia" | `which ngrok` - verificar instalación |
| "URL diferente cada vez" | Normal en plan free de ngrok |
| "Sesión expirada" | Reinicia ngrok: `bash deploy-ngrok.sh` |
| "HTTPS sin certificado" | Normal, aceptar excepción en navegador |

## 💡 Pro Tips

1. **Abre incógnito** para evitar cache del navegador
2. **Prueba en móvil** usando la URL de ngrok
3. **Descarga screenshot** de la URL por si falla
4. **Comparte URL** directo o por QR (si quieres)
5. **Ten respaldo** de videos/screenshots

## 🛠️ Debugging

Ver logs de ngrok:
```bash
tail -f ngrok-demo.log
```

Ver logs de Docker:
```bash
docker-compose logs -f web
docker-compose logs -f db
```

Probar endpoint de artistas:
```bash
curl https://xxxx-xxxx-xxxx.ngrok.io/api/artistas_famosos.php
```

## 📊 Estado Actual

✅ Todo está corriendo:
- Web: http://localhost:8080
- BD: MariaDB en Docker
- PhpMyAdmin: http://localhost:8081
- API: Respondiendo
- ngrok: Listo para usar

## 🎉 ¡Listo para Mañana!

Solo necesitas 2 comandos:

```bash
# 1. Verificar todo
bash pre-demo-check.sh

# 2. Iniciar ngrok
bash deploy-ngrok.sh
```

¡Y compartir la URL que sale en pantalla! 🚀

---

**Preguntas frecuentes:**
- ¿Cuánto tiempo dura la sesión? Depende del plan ngrok (free: 2h)
- ¿Qué pasa si se desconecta? Los datos quedan en BD, solo reinicia ngrok
- ¿Es seguro compartir la URL? Ten cuidado, estará pública
- ¿Puedo compartir con muchas personas? Sí, pero puede haber lag

¡Éxito en la demo! 🎊
