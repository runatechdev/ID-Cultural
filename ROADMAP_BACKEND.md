# 🎯 ROADMAP - BACKEND A NIVEL SENIOR

**Proyecto:** ID Cultural  
**Fecha Inicio:** 29 de Diciembre de 2025  
**Estado Actual:** ✅ Quick Wins completados - Bases de seguridad implementadas

---

## ✅ FASE 0: QUICK WINS (COMPLETADO)

### Tiempo: 1 sesión
### Estado: ✅ 100% COMPLETADO

- [x] Variables de entorno (.env)
- [x] Rate Limiting básico
- [x] Prepared Statements nativos
- [x] Sesiones seguras
- [x] Estructura de storage

**Resultado:** Seguridad pasó de D- a B+

---

## 🚀 FASE 1: ARQUITECTURA CORE (SIGUIENTE)

### Objetivo: Desacoplar y profesionalizar la base del código

### 1.1 Repository Pattern (Prioridad: 🔴 CRÍTICA)

**Tiempo estimado:** 1 sesión

**Qué hacer:**
```php
// Crear estructura
backend/
  repositories/
    BaseRepository.php          // Abstracto con CRUD base
    ArtistaRepository.php
    ObraRepository.php
    UsuarioRepository.php
    NoticiaRepository.php
  interfaces/
    RepositoryInterface.php
```

**Beneficios:**
- ✅ Lógica de BD separada de controllers
- ✅ Queries reutilizables
- ✅ Fácil de testear
- ✅ Un solo lugar para optimizar queries

**Ejemplo:**
```php
// ANTES (en controller)
$stmt = $pdo->prepare("SELECT * FROM artistas WHERE status = ?");
$stmt->execute(['validado']);

// DESPUÉS (con repository)
$artistas = $artistaRepo->findByStatus('validado');
```

---

### 1.2 Dependency Injection Container (Prioridad: 🔴 CRÍTICA)

**Tiempo estimado:** 1 sesión

**Instalar:**
```bash
composer require php-di/php-di
```

**Qué hacer:**
```php
// backend/config/container.php
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    PDO::class => function() {
        // Configuración de PDO
    },
    ArtistaRepository::class => function($c) {
        return new ArtistaRepository($c->get(PDO::class));
    },
    // etc...
]);
```

**Beneficios:**
- ✅ Eliminar `global $pdo`
- ✅ Controllers reciben dependencias
- ✅ Fácil cambiar implementaciones
- ✅ Testeable

---

### 1.3 Validación con Respect\Validation (Prioridad: 🟠 ALTA)

**Tiempo estimado:** 1 sesión

**Instalar:**
```bash
composer require respect/validation
```

**Qué hacer:**
```php
backend/
  validators/
    ArtistaValidator.php
    ObraValidator.php
    UsuarioValidator.php
```

**Ejemplo:**
```php
class ArtistaValidator {
    public function validateRegistro(array $data): array {
        $errors = [];
        
        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Email inválido';
        }
        
        if (!v::length(3, 100)->alpha(' ')->validate($data['nombre'] ?? '')) {
            $errors['nombre'] = 'Nombre debe ser 3-100 caracteres';
        }
        
        return $errors;
    }
}
```

---

### 1.4 Service Layer (Prioridad: 🟠 ALTA)

**Tiempo estimado:** 1 sesión

**Qué hacer:**
```php
backend/
  services/
    ArtistaService.php      // Lógica de negocio
    AuthService.php
    UploadService.php
    NotificationService.php
```

**Ejemplo:**
```php
class ArtistaService {
    private ArtistaRepository $repo;
    private UploadService $uploader;
    private NotificationService $notifier;
    
    public function registrarArtista(array $data): array {
        // 1. Validar datos
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        // 2. Subir foto si existe
        if (isset($data['foto'])) {
            $data['foto_perfil'] = $this->uploader->upload($data['foto']);
        }
        
        // 3. Crear en BD
        $artista = $this->repo->create($data);
        
        // 4. Notificar validadores
        $this->notifier->notifyNewArtista($artista);
        
        return $artista;
    }
}
```

**Beneficios:**
- ✅ Controllers delgados (solo routing)
- ✅ Lógica de negocio centralizada
- ✅ Reutilizable
- ✅ Testeable

---

## 🧪 FASE 2: TESTING (PARALELO A FASE 1)

### 2.1 PHPUnit Setup (Prioridad: 🟠 ALTA)

**Tiempo estimado:** 1 sesión

**Instalar:**
```bash
composer require --dev phpunit/phpunit
composer require --dev fakerphp/faker  # Para datos falsos
```

**Estructura:**
```
tests/
  Unit/
    Repositories/
      ArtistaRepositoryTest.php
    Services/
      ArtistaServiceTest.php
    Validators/
      ArtistaValidatorTest.php
  Integration/
    Api/
      ArtistaApiTest.php
  bootstrap.php
```

**Objetivo inicial:** >50% cobertura en repositories y services

---

### 2.2 Tests de APIs (Prioridad: 🟡 MEDIA)

**Qué testear:**
- Autenticación (login, logout, tokens)
- CRUD de artistas
- Validación de perfiles
- Rate limiting
- Manejo de errores

---

## 🔒 FASE 3: SEGURIDAD AVANZADA

### 3.1 JWT Authentication (Prioridad: 🟠 ALTA)

**Instalar:**
```bash
composer require firebase/php-jwt
```

**Qué hacer:**
```php
backend/
  services/
    JWTService.php          // Generar/validar tokens
    RefreshTokenService.php // Refresh tokens
  middleware/
    JWTMiddleware.php       // Validar en cada request
```

**Beneficios:**
- ✅ Stateless (no depende de sesiones)
- ✅ APIs escalables
- ✅ Tokens con expiración
- ✅ Refresh tokens

---

### 3.2 RBAC Granular (Prioridad: 🟡 MEDIA)

**Qué hacer:**
- Tabla `permissions` en BD
- Middleware `CheckPermission`
- Decoradores `@RequirePermission('artistas:update')`

---

### 3.3 Upload Security Avanzado (Prioridad: 🟠 ALTA)

**Qué hacer:**
```php
class UploadService {
    // 1. Validar MIME real (no extensión)
    // 2. Escanear con ClamAV (opcional)
    // 3. Generar nombres únicos (hash)
    // 4. Guardar fuera de webroot
    // 5. Servir via PHP (con auth)
}
```

---

## ⚡ FASE 4: PERFORMANCE

### 4.1 Redis Cache (Prioridad: 🟡 MEDIA)

**Instalar:**
```bash
composer require predis/predis
```

**Qué cachear:**
- Listados de artistas validados
- Stats públicas
- Búsquedas frecuentes
- Sesiones (opcional)

---

### 4.2 Query Optimization (Prioridad: 🟡 MEDIA)

**Qué hacer:**
- Agregar índices a BD
- Usar EXPLAIN en queries lentas
- Implementar paginación en todos los listados
- Lazy loading de relaciones

---

## 🔍 FASE 5: OBSERVABILIDAD

### 5.1 Logging Estructurado (Prioridad: 🟠 ALTA)

**Instalar:**
```bash
composer require monolog/monolog
```

**Qué loggear:**
- Errores (con contexto)
- Intentos de login
- Cambios en perfiles
- Rate limit blocks
- Acciones de admin

---

### 5.2 Health Check Endpoints (Prioridad: 🟡 MEDIA)

```php
GET /api/health
{
  "status": "ok",
  "database": "connected",
  "redis": "connected",
  "disk_space": "85%",
  "uptime": "3d 12h"
}
```

---

## 🚀 FASE 6: DEVOPS

### 6.1 CI/CD Pipeline (Prioridad: 🟡 MEDIA)

**GitHub Actions:**
```yaml
on: [push]
jobs:
  test:
    - composer install
    - phpunit
    - phpstan analyse
    - php-cs-fixer
  deploy:
    - ssh to server
    - git pull
    - composer install --no-dev
    - run migrations
```

---

### 6.2 Migraciones Versionadas (Prioridad: 🟠 ALTA)

**Instalar:**
```bash
composer require robmorgan/phinx
```

**Ejemplo:**
```php
// database/migrations/20251229_add_permissions.php
class AddPermissions extends AbstractMigration {
    public function change() {
        $table = $this->table('permissions');
        $table->addColumn('role', 'string')
              ->addColumn('resource', 'string')
              ->addColumn('action', 'string')
              ->create();
    }
}
```

---

## 📊 PRIORIZACIÓN SUGERIDA

### Sesión 1 (HOY - si querés seguir)
- [ ] Repository Pattern básico (ArtistaRepository)
- [ ] Refactor 1 controller para usar Repository

### Sesión 2
- [ ] Dependency Injection Container
- [ ] Refactor todos los controllers

### Sesión 3
- [ ] Validación con Respect
- [ ] Service Layer (ArtistaService)

### Sesión 4
- [ ] PHPUnit setup
- [ ] Tests de Repositories

### Sesión 5
- [ ] JWT Authentication
- [ ] Logging con Monolog

### Sesión 6
- [ ] Redis Cache
- [ ] Upload Security

### Sesión 7
- [ ] Migraciones con Phinx
- [ ] CI/CD básico

---

## 🎯 CUANDO COMPLETEMOS ESTO

**El backend estará:**
- ✅ Arquitectura SOLID
- ✅ 70%+ test coverage
- ✅ Seguridad A+
- ✅ Performance optimizada
- ✅ Logs completos
- ✅ CI/CD automatizado
- ✅ Escalable

**Recién ahí:** Frontend moderno (Vue/React)

---

## 💡 NOTAS

- No hay estimaciones de tiempo rígidas
- Cada fase se hace cuando querés
- Podemos hacer una cosa por sesión
- Lo importante: ir avanzando de a poco
- El frontend se hace AL FINAL

---

**¿Querés seguir HOY con Repository Pattern?** Es el siguiente paso lógico después de Quick Wins.
