# 🔌 DOCUMENTACIÓN DE API PÚBLICA - ID CULTURAL

## 📋 Índice
- [Introducción](#introducción)
- [Autenticación](#autenticación)
- [Rate Limiting](#rate-limiting)
- [Endpoints Disponibles](#endpoints-disponibles)
- [Ejemplos de Uso](#ejemplos-de-uso)
- [Códigos de Respuesta](#códigos-de-respuesta)
- [SDKs y Librerías](#sdks-y-librerías)

---

## 🎯 Introducción

La API de ID Cultural permite acceder a información sobre artistas, obras y contenido cultural de manera programática.

**Base URL:**
- Desarrollo: `http://localhost:8080/api`
- Producción: `https://idcultural.com/api`

**Formato de Respuesta:** JSON

---

## 🔐 Autenticación

La mayoría de endpoints públicos no requieren autenticación. Para endpoints que requieren autenticación:

### Header de Autenticación
```http
Authorization: Bearer YOUR_API_KEY
```

### Obtener API Key
Contactar a: soporte@idcultural.com

---

## ⚡ Rate Limiting

Para prevenir abuso, la API implementa rate limiting:

- **Límite por defecto:** 100 requests por minuto por IP
- **Límite autenticado:** 500 requests por minuto con API key

### Headers de Rate Limit
```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1699564800
```

### Respuesta cuando se excede el límite
```json
{
  "success": false,
  "message": "Demasiadas solicitudes. Intente más tarde.",
  "code": 429
}
```

---

## 📡 Endpoints Disponibles

### 1. **Artistas**

#### GET `/artistas.php?action=get`
Obtener lista de artistas validados

**Parámetros:**
- `status` (opcional): `validado`, `pendiente` (default: `validado`)
- `categoria` (opcional): Filtrar por categoría
- `municipio` (opcional): Filtrar por municipio

**Ejemplo:**
```bash
curl http://localhost:8080/api/artistas.php?action=get&status=validado
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre_completo": "Juan Pérez",
      "nombre_artistico": "El Maestro",
      "municipio": "Medellín",
      "categoria": "Música",
      "biografia": "Artista con 20 años de experiencia...",
      "foto_perfil": "/uploads/artistas/juan.jpg",
      "estado": "validado",
      "fecha_registro": "2025-01-15 10:30:00"
    }
  ]
}
```

---

### 2. **Obras**

#### GET `/get_obras_wiki.php`
Obtener obras para wiki

**Parámetros:**
- `categoria` (opcional): Filtrar por categoría
- `artista_id` (opcional): Obras de un artista específico

**Ejemplo:**
```bash
curl http://localhost:8080/api/get_obras_wiki.php?categoria=Pintura
```

**Respuesta:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "artista_id": 1,
      "artista_nombre": "Juan Pérez",
      "titulo": "Atardecer en el Valle",
      "descripcion": "Pintura al óleo...",
      "tipo": "Pintura",
      "multimedia": ["/uploads/obras/obra1.jpg"]
    }
  ]
}
```

---

### 3. **Publicaciones**

#### GET `/get_publicaciones.php`
Obtener publicaciones de artistas

**Parámetros:**
- `estado` (opcional): `validado`, `pendiente`
- `categoria` (opcional): Filtrar por categoría
- `limit` (opcional): Número de resultados (default: 20)
- `offset` (opcional): Paginación (default: 0)

**Ejemplo:**
```bash
curl http://localhost:8080/api/get_publicaciones.php?estado=validado&limit=10
```

---

### 4. **Noticias**

#### GET `/noticias.php?action=get_all`
Obtener lista de noticias

**Parámetros:**
- `limit` (opcional): Número de resultados

**Ejemplo:**
```bash
curl http://localhost:8080/api/noticias.php?action=get_all&limit=5
```

---

### 5. **Analytics (Público)**

#### POST `/analytics.php`
Registrar eventos, visitas y búsquedas

**Registrar visita a página:**
```bash
curl -X POST http://localhost:8080/api/analytics.php \
  -d "action=track_page&pagina=/wiki.php&duracion=30"
```

**Registrar evento:**
```bash
curl -X POST http://localhost:8080/api/analytics.php \
  -d "action=track_event&categoria=Click&accion=Boton&etiqueta=Compartir"
```

**Registrar búsqueda:**
```bash
curl -X POST http://localhost:8080/api/analytics.php \
  -d "action=track_search&termino=musica&resultados=15"
```

---

## 💡 Ejemplos de Uso

### JavaScript (Fetch API)

```javascript
// Obtener artistas
async function getArtistas() {
  try {
    const response = await fetch('http://localhost:8080/api/artistas.php?action=get');
    const data = await response.json();
    
    if (data.success) {
      console.log('Artistas:', data.data);
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// Registrar evento
async function trackEvent(categoria, accion) {
  const formData = new URLSearchParams();
  formData.append('action', 'track_event');
  formData.append('categoria', categoria);
  formData.append('accion', accion);
  
  await fetch('http://localhost:8080/api/analytics.php', {
    method: 'POST',
    body: formData
  });
}
```

### Python

```python
import requests

# Obtener artistas
response = requests.get('http://localhost:8080/api/artistas.php', params={
    'action': 'get',
    'status': 'validado'
})

if response.json()['success']:
    artistas = response.json()['data']
    print(artistas)

# Registrar evento
requests.post('http://localhost:8080/api/analytics.php', data={
    'action': 'track_event',
    'categoria': 'API',
    'accion': 'Consulta'
})
```

### PHP

```php
<?php
// Obtener artistas
$url = 'http://localhost:8080/api/artistas.php?action=get';
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data['success']) {
    $artistas = $data['data'];
    print_r($artistas);
}

// Registrar evento
$ch = curl_init('http://localhost:8080/api/analytics.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'action' => 'track_event',
    'categoria' => 'API',
    'accion' => 'Consulta'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
?>
```

---

## 📊 Códigos de Respuesta

| Código | Descripción |
|--------|-------------|
| 200 | Éxito |
| 400 | Solicitud inválida |
| 401 | No autenticado |
| 403 | Acceso denegado |
| 404 | Recurso no encontrado |
| 429 | Demasiadas solicitudes (rate limit) |
| 500 | Error del servidor |

---

## 🛠️ SDKs y Librerías

### JavaScript SDK

Usar el archivo `/public/static/js/api-sdk.js`:

```javascript
const api = new IDCulturalAPI();

// Obtener artistas
const artistas = await api.artistas.getAll();

// Buscar artistas
const resultados = await api.artistas.search('música');

// Track evento
api.analytics.trackEvent('Click', 'Botón', 'Compartir');
```

---

## 📖 Documentación Interactiva

Visitar: `http://localhost:8080/api/docs.html`

Para ver la documentación Swagger interactiva donde puedes probar los endpoints directamente.

---

## 🆘 Soporte

**Email:** soporte@idcultural.com  
**GitHub Issues:** https://github.com/runatechdev/ID-Cultural/issues

---

## 📄 Licencia

MIT License - Ver LICENSE file para más detalles

---

*Última actualización: 10 de Noviembre de 2025*
