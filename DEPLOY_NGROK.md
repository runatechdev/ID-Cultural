# 📱 Guía de Despliegue en ngrok para Demo

## ¿Qué es ngrok?
ngrok es una herramienta que expone tu localhost a internet. Es perfecta para demos, testing y mostrar el proyecto a otros sin necesidad de un servidor en la nube.

## Pasos para Desplegar

### 1️⃣ **Asegúrate que la aplicación está corriendo**
```bash
cd /home/runatechdev/Documentos/Github/ID-Cultural

# Si usas Docker:
docker-compose up -d

# O si usas PHP local:
php -S localhost:8080
```

Verifica que funcione:
```bash
curl http://localhost:8080
```

### 2️⃣ **Ejecutar ngrok**

**Opción A: Usando el script (recomendado)**
```bash
./deploy-ngrok.sh
```

**Opción B: Comando directo**
```bash
ngrok http 8080
```

### 3️⃣ **Ver la URL pública**
Después de ejecutar ngrok, verás algo como:

```
ngrok                                       (Ctrl+C to quit)

Session Status                online
Account                       [tu cuenta]
Version                        3.x.x
Region                         us
Forwarding                     https://xxxx-xx-xxx-xxx-xx.ngrok.io -> http://localhost:8080
```

**La URL `https://xxxx-xx-xxx-xxx-xx.ngrok.io` es tu app en internet** 🎉

### 4️⃣ **Compartir la demo**
Copia el enlace y comparte:
- ✅ Funciona en cualquier navegador
- ✅ Desde cualquier dispositivo (móvil, tablet, PC)
- ✅ Desde cualquier lugar del mundo
- ✅ HTTPS automático (seguro)

## 📝 Importante para la Demo

### Bases de Datos
Asegúrate de que:
```bash
# La BD está corriendo (si usas Docker):
docker-compose ps

# Verifica la conexión:
mysql -h localhost -u root -p idcultural -e "SELECT COUNT(*) FROM artistas;"
```

### URLs internas en el código
Si hay URLs hardcodeadas con `localhost:8080`, cambiarlas dinámicamente:
- Usa `BASE_URL` en lugar de URLs hardcodeadas
- En `config.php` debería estar bien configurado

### Session/Cookies
```php
// En config.php o header, asegúrate que las cookies funcionan:
session_set_cookie_params([
    'secure' => true,  // Para HTTPS de ngrok
    'httponly' => true,
    'samesite' => 'Lax'
]);
```

## 🔒 Seguridad en Demo

### Cosas a considerar:
1. **ngrok genera URLs públicas** - La demo está en internet
2. **Límite de tiempo** - Cada sesión tiene un tiempo límite (revisa plan de ngrok)
3. **Datos de prueba** - Usa datos de demo, NO reales
4. **Credenciales** - Los usuarios/contraseñas están expuestos

### Opciones para proteger:
```bash
# Agregar contraseña HTTP Basic Auth:
ngrok http 8080 --basic-auth "user:password"

# Restringir a ciertos IPs:
ngrok http 8080 --ip-restriction "203.0.113.0,192.0.2.0"

# URL con patrón específico:
ngrok http 8080 --domain="tu-dominio.ngrok.io"
```

## 💡 Consejos para Demo Exitosa

✅ **Antes de la demo:**
- [ ] Verifica que localhost:8080 funcione perfectamente
- [ ] Prueba el login y las funciones principales
- [ ] Prepara datos de demo listos
- [ ] Ten la URL de ngrok copiada

✅ **Durante la demo:**
- [ ] Usa conexión a internet estable
- [ ] Abre la URL en incógnito (para evitar cache)
- [ ] Ten un respaldo (screenshots/videos) por si falla ngrok

✅ **Después de la demo:**
- [ ] Termina el proceso de ngrok (Ctrl+C)
- [ ] Verifica logs si hay errores
- [ ] Documenta feedback recibido

## 🚀 Ejemplo Completo

```bash
# Terminal 1: Iniciar aplicación
cd /home/runatechdev/Documentos/Github/ID-Cultural
docker-compose up -d

# Terminal 2: Iniciar ngrok
./deploy-ngrok.sh

# Espera a ver:
# Forwarding    https://xxxx-xxxx-xxxx.ngrok.io -> http://localhost:8080

# Terminal 3: Abre el navegador con la URL
```

## ❌ Solución de Problemas

**"No puedo acceder a la URL"**
- Verifica que localhost:8080 funciona: `curl http://localhost:8080`
- Verifica que ngrok está corriendo: `ps aux | grep ngrok`
- Revisa los logs de ngrok

**"La BD no responde"**
- Verifica que Docker está corriendo: `docker ps`
- Revisa logs: `docker-compose logs db`

**"Sesión expirada"**
- ngrok tiene límites en el plan free
- Reinicia: `./deploy-ngrok.sh` nuevamente

**"HTTPS se ve inseguro"**
- Es normal, ngrok genera certificados auto-firmados
- En navegador, acepta la excepción de seguridad

## 📞 Contacto/Soporte

Si necesitas ayuda durante la demo:
1. Verifica que ngrok está corriendo
2. Reinicia si hay errores
3. Usa `curl -v` para debuggear

---

¡Buena suerte con tu demo! 🎉
