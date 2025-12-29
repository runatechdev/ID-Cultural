# 🚀 PLAN DE MEJORA A NIVEL SENIOR - ID CULTURAL
## Auditoría Técnica y Hoja de Ruta

**Fecha:** 29 de Diciembre de 2025  
**Cliente:** Subsecretaría de Cultura - Santiago del Estero  
**Estado Actual:** Proyecto Académico → **Objetivo:** Sistema Productivo Gubernamental  
**Ingeniero:** Análisis de Arquitectura y Backend PHP

---

## 📊 DIAGNÓSTICO ACTUAL

### ✅ FORTALEZAS DEL PROYECTO
1. **Arquitectura Base Sólida**: MVC parcial con separación backend/frontend
2. **APIs REST**: 7 controladores funcionales (Auth, Artista, Obra, Noticia, Admin, Stats, Logs)
3. **Autenticación**: Sistema de roles implementado (artista, validador, editor, admin)
4. **Base de Datos**: Esquema bien diseñado con sistema de validación de perfiles
5. **Docker**: Entorno containerizado con Docker Compose
6. **Documentación**: Amplia documentación técnica en `/docs`

### ⚠️ PROBLEMAS CRÍTICOS IDENTIFICADOS

#### 🔴 **NIVEL CRÍTICO - SEGURIDAD**
1. **Credenciales hardcodeadas** en `config.php` y `docker-compose.yml`
2. **Sin validación de inputs** consistente en backend
3. **SQL Injection vulnerable** (uso de concatenación en algunos controllers)
4. **Sin HTTPS enforcement** en producción
5. **Sesiones sin configuración segura** (httponly, secure flags)
6. **Sin rate limiting** en APIs públicas
7. **Uploads sin sanitización** adecuada

#### 🟠 **NIVEL ALTO - ARQUITECTURA**
1. **Código procedural mezclado** con POO (archivos en `/public`)
2. **Acoplamiento alto**: Controllers acceden directamente a `$pdo` global
3. **Sin patrón Repository**: Lógica de BD mezclada con controllers
4. **Sin Dependency Injection**: Todo depende de `global $pdo`
5. **Autoloader manual**: No usa Composer PSR-4 correctamente
6. **Sin manejo de errores** centralizado
7. **Sin logging estructurado**

#### 🟡 **NIVEL MEDIO - CALIDAD**
1. **Zero tests**: Sin PHPUnit configurado ni tests escritos
2. **Sin validación de esquemas** (JSON Schema, DTOs)
3. **Sin caché**: Queries repetitivas sin optimización
4. **Assets sin versionado** correcto (cache busting inconsistente)
5. **Sin CI/CD pipeline**
6. **Sin migraciones de BD** versionadas (solo SQL sueltos)
7. **Sin monitoreo/observabilidad**

---

## 🎯 ESTRATEGIA DE MEJORA: ORDEN RECOMENDADO

### **RESPUESTA A TU PREGUNTA: ¿Backend o Frontend primero?**

**🏗️ BACKEND PRIMERO - 100%**

**Razones:**
1. **Seguridad es prioridad 1** para un sistema gubernamental
2. El frontend depende de APIs estables y seguras
3. Rediseñar frontend sin backend sólido = rehacer todo dos veces
4. Base de datos y lógica de negocio son el core del sistema
5. Frontend puede mejorarse incrementalmente después

---

## 📋 FASE 1: REFACTORIZACIÓN BACKEND (6-8 semanas)

### **Semana 1-2: Fundamentos y Seguridad**

#### 1.1 **Sistema de Configuración Profesional**
```bash
Objetivo: Eliminar credenciales del código
Prioridad: 🔴 CRÍTICA
Tiempo: 2 días
```

**Tareas:**
- [ ] Crear sistema de `.env` con `vlucas/phpdotenv`
- [ ] Mover TODAS las credenciales a `.env`
- [ ] Configuración por entornos (dev, staging, prod)
- [ ] Validación de variables requeridas al inicio
- [ ] Documentar en `.env.example`

**Archivos a crear:**
```
/.env (gitignored)
/.env.example (template)
/.env.production.example
/.env.testing
/backend/config/Environment.php (clase cargadora)
```

**Stack sugerido:**
```json
{
  "require": {
    "vlucas/phpdotenv": "^5.5",
    "symfony/dotenv": "^6.0"
  }
}
```

#### 1.2 **Dependency Injection Container**
```bash
Objetivo: Desacoplar componentes
Prioridad: 🔴 CRÍTICA
Tiempo: 3 días
```

**Tareas:**
- [ ] Instalar PHP-DI o Symfony DI
- [ ] Crear Service Container
- [ ] Registrar servicios (PDO, Mailer, Logger, Cache)
- [ ] Refactor controllers para recibir dependencias
- [ ] Eliminar `global $pdo`

**Ejemplo:**
```php
// backend/config/container.php
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    PDO::class => function() {
        return new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    },
    // ... más servicios
]);

return $containerBuilder->build();
```

#### 1.3 **Capa de Repositorios**
```bash
Objetivo: Separar lógica de datos
Prioridad: 🟠 ALTA
Tiempo: 4 días
```

**Estructura:**
```
backend/
  repositories/
    BaseRepository.php (abstracto)
    ArtistaRepository.php
    ObraRepository.php
    UsuarioRepository.php
    NoticiaRepository.php
  interfaces/
    RepositoryInterface.php
```

**Ejemplo:**
```php
// backend/repositories/ArtistaRepository.php
namespace Backend\Repositories;

class ArtistaRepository extends BaseRepository
{
    protected string $table = 'artistas';
    
    public function findValidados(array $filters = []): array
    {
        $query = "SELECT * FROM {$this->table} WHERE status_perfil = :status";
        $params = ['status' => 'validado'];
        
        // Agregar filtros dinámicos
        if (isset($filters['provincia'])) {
            $query .= " AND provincia = :provincia";
            $params['provincia'] = $filters['provincia'];
        }
        
        return $this->query($query, $params);
    }
    
    public function updateStatus(int $id, string $status, ?string $motivo = null): bool
    {
        return $this->update($id, [
            'status_perfil' => $status,
            'motivo_rechazo' => $motivo,
            'fecha_validacion' => date('Y-m-d H:i:s')
        ]);
    }
}
```

#### 1.4 **Validación y Sanitización**
```bash
Objetivo: Input validation robusto
Prioridad: 🔴 CRÍTICA
Tiempo: 3 días
```

**Stack:**
```json
{
  "require": {
    "respect/validation": "^2.2",
    "symfony/validator": "^6.0"
  }
}
```

**Crear:**
```
backend/
  validators/
    ArtistaValidator.php
    ObraValidator.php
    UsuarioValidator.php
  rules/
    CustomValidationRules.php (reglas propias)
```

**Ejemplo:**
```php
// backend/validators/ArtistaValidator.php
namespace Backend\Validators;

use Respect\Validation\Validator as v;

class ArtistaValidator
{
    public function validateRegistro(array $data): array
    {
        $errors = [];
        
        // Email
        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Email inválido';
        }
        
        // Nombre (solo letras y espacios)
        if (!v::alpha(' ')->length(3, 100)->validate($data['nombre'] ?? '')) {
            $errors['nombre'] = 'Nombre debe tener entre 3-100 caracteres';
        }
        
        // Provincia (debe existir en lista)
        $provincias = ['Santiago del Estero', 'Buenos Aires', ...];
        if (!in_array($data['provincia'] ?? '', $provincias)) {
            $errors['provincia'] = 'Provincia inválida';
        }
        
        return $errors;
    }
}
```

---

### **Semana 3-4: Seguridad y Autenticación**

#### 2.1 **Sistema de Autenticación Robusto**
```bash
Objetivo: JWT + Refresh Tokens
Prioridad: 🔴 CRÍTICA
Tiempo: 5 días
```

**Stack:**
```json
{
  "require": {
    "firebase/php-jwt": "^6.4",
    "paragonie/password_lock": "^4.0"
  }
}
```

**Implementar:**
- [ ] JWT para APIs (stateless)
- [ ] Refresh tokens en BD
- [ ] Middleware de autenticación
- [ ] Rate limiting por IP/usuario
- [ ] Logs de intentos fallidos
- [ ] Bloqueo temporal tras N intentos

**Estructura:**
```
backend/
  services/
    AuthService.php
    JWTService.php
    TokenService.php
  middleware/
    AuthMiddleware.php
    RoleMiddleware.php
    RateLimitMiddleware.php
```

#### 2.2 **RBAC (Role-Based Access Control)**
```bash
Objetivo: Permisos granulares
Prioridad: 🟠 ALTA
Tiempo: 3 días
```

**Tabla nueva:**
```sql
CREATE TABLE permisos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rol ENUM('artista', 'validador', 'editor', 'admin'),
    recurso VARCHAR(50), -- 'artistas', 'obras', 'usuarios'
    accion VARCHAR(20),  -- 'create', 'read', 'update', 'delete'
    INDEX idx_rol_recurso (rol, recurso)
);

INSERT INTO permisos (rol, recurso, accion) VALUES
('artista', 'artistas', 'read'),
('artista', 'artistas', 'update'), -- solo su perfil
('validador', 'artistas', 'read'),
('validador', 'artistas', 'update'), -- cambiar status
('editor', 'artistas', 'read'),
('editor', 'artistas', 'update'),
('editor', 'artistas', 'delete'),
('admin', '*', '*'); -- todos los permisos
```

**Middleware:**
```php
class RoleMiddleware
{
    public function handle($request, Closure $next, string $recurso, string $accion)
    {
        $user = $request->user; // del AuthMiddleware
        
        if (!$this->hasPermission($user->rol, $recurso, $accion)) {
            throw new UnauthorizedException('No tiene permisos');
        }
        
        return $next($request);
    }
}
```

#### 2.3 **Seguridad de Uploads**
```bash
Objetivo: Prevenir malware/webshells
Prioridad: 🔴 CRÍTICA
Tiempo: 2 días
```

**Crear:**
```php
// backend/services/UploadService.php
namespace Backend\Services;

class UploadService
{
    private array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf'
    ];
    
    private int $maxSize = 5 * 1024 * 1024; // 5MB
    
    public function handleUpload(array $file, string $tipo = 'imagen'): array
    {
        // 1. Validar tipo MIME (del contenido, no de la extensión)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($realMime, $this->allowedMimes)) {
            throw new InvalidFileException('Tipo de archivo no permitido');
        }
        
        // 2. Validar tamaño
        if ($file['size'] > $this->maxSize) {
            throw new InvalidFileException('Archivo muy grande');
        }
        
        // 3. Generar nombre seguro (sin extensión original)
        $hash = hash('sha256', $file['tmp_name'] . time());
        $extension = $this->getExtensionFromMime($realMime);
        $filename = $hash . '.' . $extension;
        
        // 4. Mover a directorio seguro (fuera de webroot)
        $uploadDir = $_ENV['UPLOAD_DIR'] . '/' . $tipo;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $destination = $uploadDir . '/' . $filename;
        
        // 5. Escanear con ClamAV si está disponible (opcional)
        if ($this->clamavEnabled) {
            $this->scanVirus($file['tmp_name']);
        }
        
        // 6. Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new UploadException('Error al guardar archivo');
        }
        
        // 7. Registrar en BD
        return [
            'filename' => $filename,
            'original_name' => $file['name'],
            'mime_type' => $realMime,
            'size' => $file['size'],
            'path' => $destination
        ];
    }
}
```

---

### **Semana 5-6: Testing y Calidad**

#### 3.1 **PHPUnit: Tests Unitarios**
```bash
Objetivo: Cobertura >70%
Prioridad: 🟠 ALTA
Tiempo: 5 días
```

**Configurar PHPUnit:**
```xml
<!-- phpunit.xml -->
<phpunit bootstrap="tests/bootstrap.php">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory>backend</directory>
        </include>
        <exclude>
            <directory>backend/config</directory>
            <file>backend/helpers/legacy.php</file>
        </exclude>
    </coverage>
</phpunit>
```

**Escribir tests:**
```php
// tests/Unit/Repositories/ArtistaRepositoryTest.php
namespace Tests\Unit\Repositories;

use PHPUnit\Framework\TestCase;
use Backend\Repositories\ArtistaRepository;

class ArtistaRepositoryTest extends TestCase
{
    private ArtistaRepository $repo;
    
    protected function setUp(): void
    {
        // Mock PDO o usar BD de pruebas
        $this->repo = new ArtistaRepository($this->getMockPDO());
    }
    
    public function testFindValidadosReturnsSoloValidados()
    {
        $result = $this->repo->findValidados();
        
        $this->assertIsArray($result);
        foreach ($result as $artista) {
            $this->assertEquals('validado', $artista['status_perfil']);
        }
    }
    
    public function testUpdateStatusCambiaEstado()
    {
        $result = $this->repo->updateStatus(1, 'rechazado', 'Datos incompletos');
        
        $this->assertTrue($result);
        
        $artista = $this->repo->find(1);
        $this->assertEquals('rechazado', $artista['status_perfil']);
        $this->assertEquals('Datos incompletos', $artista['motivo_rechazo']);
    }
}
```

#### 3.2 **Logging Estructurado**
```bash
Objetivo: Monitoreo y debugging
Prioridad: 🟡 MEDIA
Tiempo: 2 días
```

**Stack:**
```json
{
  "require": {
    "monolog/monolog": "^3.0"
  }
}
```

**Configurar:**
```php
// backend/config/logging.php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\JsonFormatter;

$logger = new Logger('idcultural');

// Development: log to stdout
if ($_ENV['APP_ENV'] === 'development') {
    $handler = new StreamHandler('php://stdout', Logger::DEBUG);
} else {
    // Production: rotate logs daily
    $handler = new RotatingFileHandler(
        $_ENV['LOG_PATH'] . '/app.log',
        30, // keep 30 days
        Logger::WARNING
    );
}

$handler->setFormatter(new JsonFormatter());
$logger->pushHandler($handler);

return $logger;
```

**Usar en controllers:**
```php
class ArtistaController
{
    private Logger $logger;
    
    public function __construct(ArtistaRepository $repo, Logger $logger)
    {
        $this->repo = $repo;
        $this->logger = $logger;
    }
    
    public function register()
    {
        try {
            $data = $this->validateInput();
            $artista = $this->repo->create($data);
            
            $this->logger->info('Artista registrado', [
                'artista_id' => $artista['id'],
                'email' => $artista['email'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            
            return $this->json(['success' => true, 'data' => $artista]);
        } catch (\Exception $e) {
            $this->logger->error('Error en registro de artista', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $data ?? null
            ]);
            
            throw $e;
        }
    }
}
```

---

### **Semana 7-8: Performance y DevOps**

#### 4.1 **Sistema de Caché**
```bash
Objetivo: Reducir carga de BD
Prioridad: 🟡 MEDIA
Tiempo: 3 días
```

**Stack:**
```json
{
  "require": {
    "predis/predis": "^2.0", // Redis
    "symfony/cache": "^6.0"
  }
}
```

**Implementar:**
```php
// backend/services/CacheService.php
namespace Backend\Services;

use Predis\Client as Redis;

class CacheService
{
    private Redis $redis;
    private int $defaultTTL = 3600; // 1 hora
    
    public function __construct()
    {
        $this->redis = new Redis([
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);
    }
    
    public function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }
    
    public function invalidatePattern(string $pattern): void
    {
        $keys = $this->redis->keys($pattern);
        if (!empty($keys)) {
            $this->redis->del($keys);
        }
    }
}
```

**Usar en repositorios:**
```php
class ArtistaRepository
{
    public function findValidados(array $filters = []): array
    {
        $cacheKey = 'artistas:validados:' . md5(json_encode($filters));
        
        return $this->cache->remember($cacheKey, function() use ($filters) {
            return $this->queryDatabase($filters);
        }, 1800); // 30 minutos
    }
    
    public function update(int $id, array $data): bool
    {
        $result = parent::update($id, $data);
        
        if ($result) {
            // Invalidar cachés relacionados
            $this->cache->invalidatePattern('artistas:*');
            $this->cache->invalidatePattern("artista:{$id}:*");
        }
        
        return $result;
    }
}
```

#### 4.2 **Migraciones de BD**
```bash
Objetivo: Versionado de esquema
Prioridad: 🟠 ALTA
Tiempo: 2 días
```

**Stack:**
```json
{
  "require": {
    "phinx/phinx": "^0.14"
  }
}
```

**Crear migración:**
```bash
vendor/bin/phinx create AgregarPermisos
```

```php
// database/migrations/20251229_agregar_permisos.php
use Phinx\Migration\AbstractMigration;

class AgregarPermisos extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('permisos');
        $table->addColumn('rol', 'enum', [
                'values' => ['artista', 'validador', 'editor', 'admin']
            ])
            ->addColumn('recurso', 'string', ['limit' => 50])
            ->addColumn('accion', 'string', ['limit' => 20])
            ->addIndex(['rol', 'recurso'])
            ->create();
    }
}
```

#### 4.3 **CI/CD con GitHub Actions**
```bash
Objetivo: Deploy automatizado
Prioridad: 🟡 MEDIA
Tiempo: 3 días
```

**Crear:**
```yaml
# .github/workflows/ci.yml
name: CI Pipeline

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mariadb:10.5
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: idcultural_test
        ports:
          - 3306:3306
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo, pdo_mysql, mbstring
          coverage: xdebug
      
      - name: Install dependencies
        run: composer install --prefer-dist --no-progress
      
      - name: Run migrations
        run: vendor/bin/phinx migrate -e testing
      
      - name: Run PHPUnit tests
        run: vendor/bin/phpunit --coverage-clover coverage.xml
      
      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage.xml
      
      - name: Run PHP CS Fixer
        run: vendor/bin/php-cs-fixer fix --dry-run --diff
      
      - name: Run PHPStan
        run: vendor/bin/phpstan analyse backend --level=6

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Deploy to production
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/idcultural
            git pull origin main
            composer install --no-dev --optimize-autoloader
            vendor/bin/phinx migrate -e production
            php artisan cache:clear
            sudo systemctl reload php-fpm
```

---

## 📋 FASE 2: OPTIMIZACIÓN FRONTEND (3-4 semanas)

### **Esta fase se hace DESPUÉS del backend**

#### Mejoras Principales:
1. **Migrar a framework moderno** (Vue.js 3 / React)
2. **Component-based architecture**
3. **State management** (Pinia / Zustand)
4. **Build system** (Vite)
5. **CSS framework** (TailwindCSS)
6. **Progressive Web App** (PWA)

---

## 🔧 HERRAMIENTAS Y STACK FINAL RECOMENDADO

### **Backend**
```json
{
  "require": {
    "php": "^8.2",
    "ext-pdo": "*",
    "ext-mbstring": "*",
    "ext-json": "*",
    
    "vlucas/phpdotenv": "^5.5",
    "php-di/php-di": "^7.0",
    "respect/validation": "^2.2",
    "firebase/php-jwt": "^6.4",
    "monolog/monolog": "^3.0",
    "predis/predis": "^2.0",
    "phpmailer/phpmailer": "^7.0",
    "guzzlehttp/guzzle": "^7.5"
  },
  "require-dev": {
    "phpunit/phpunit": "^10.0",
    "phpstan/phpstan": "^1.10",
    "friendsofphp/php-cs-fixer": "^3.14",
    "phinx/phinx": "^0.14",
    "fakerphp/faker": "^1.21"
  }
}
```

### **Infrastructure**
```yaml
# docker-compose.yml (mejorado)
services:
  app:
    build:
      context: .
      dockerfile: docker/Dockerfile
    volumes:
      - .:/var/www/app
      - ./storage/logs:/var/www/app/logs
    environment:
      - APP_ENV=production
    depends_on:
      - db
      - redis
  
  db:
    image: mariadb:10.11
    environment:
      MYSQL_ROOT_PASSWORD_FILE: /run/secrets/db_root_password
      MYSQL_DATABASE: idcultural
    secrets:
      - db_root_password
    volumes:
      - db_data:/var/lib/mysql
  
  redis:
    image: redis:7-alpine
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
  
  nginx:
    image: nginx:alpine
    volumes:
      - ./docker/nginx:/etc/nginx/conf.d
    ports:
      - "443:443"
    depends_on:
      - app

volumes:
  db_data:
  redis_data:

secrets:
  db_root_password:
    file: ./secrets/db_root_password.txt
```

---

## 📊 MÉTRICAS DE ÉXITO

### **Antes (Estado Actual)**
- ❌ Security Score: **D** (credenciales en código)
- ❌ Code Quality: **C** (sin tests, código acoplado)
- ❌ Performance: **C** (sin caché, queries N+1)
- ❌ Maintainability: **C** (sin arquitectura clara)

### **Después (Objetivo)**
- ✅ Security Score: **A** (OWASP Top 10 cubierto)
- ✅ Code Quality: **A** (>70% coverage, PSR-12)
- ✅ Performance: **A** (<200ms response time)
- ✅ Maintainability: **A** (arquitectura limpia, documented)

---

## 💰 ESTIMACIÓN DE ESFUERZO

### **Tiempo Total: 10-12 semanas**

| Fase | Duración | Prioridad | Recursos |
|------|----------|-----------|----------|
| Backend Core (Security + Arch) | 8 semanas | 🔴 CRÍTICA | 1 Senior PHP |
| Testing + DevOps | 2 semanas | 🟠 ALTA | 1 DevOps + 1 QA |
| Frontend Refactor | 4 semanas | 🟡 MEDIA | 1 Frontend Dev |
| Documentation | 1 semana | 🟡 MEDIA | 1 Tech Writer |

### **Equipo Recomendado:**
- 1 Senior Backend PHP (full-time)
- 1 DevOps Engineer (part-time)
- 1 Frontend Developer (después de semana 8)
- 1 QA Engineer (semanas 7-12)

---

## 🚀 QUICK WINS (Cambios Inmediatos - 1 semana)

### **Top 5 mejoras de bajo esfuerzo, alto impacto:**

1. **Mover credenciales a `.env`** (1 día)
2. **Configurar HTTPS en nginx** (4 horas)
3. **Agregar prepared statements en todos los queries** (2 días)
4. **Implementar rate limiting básico** (1 día)
5. **Configurar logs estructurados** (4 horas)

---

## 📞 PRÓXIMOS PASOS

### **Para empezar AHORA:**

1. **Crear branch de desarrollo:**
   ```bash
   git checkout -b refactor/backend-senior
   ```

2. **Instalar dependencias base:**
   ```bash
   composer require vlucas/phpdotenv php-di/php-di
   composer require --dev phpunit/phpunit phpstan/phpstan
   ```

3. **Crear estructura de directorios:**
   ```bash
   mkdir -p backend/{repositories,services,validators,middleware}
   mkdir -p tests/{Unit,Integration}
   mkdir -p storage/{logs,cache,uploads}
   ```

4. **Configurar `.env`:**
   ```bash
   cp .env.example .env
   # Editar con credenciales reales
   ```

---

## 📚 DOCUMENTACIÓN DE REFERENCIA

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP The Right Way](https://phptherightway.com/)
- [PSR-12 Coding Style](https://www.php-fig.org/psr/psr-12/)
- [Symfony Best Practices](https://symfony.com/doc/current/best_practices.html)
- [Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)

---

## ✅ CONCLUSIÓN

**¿Backend o Frontend primero?** → **BACKEND 100%**

El proyecto tiene bases sólidas pero necesita refactorización profunda en:
1. **Seguridad** (crítico para gobierno)
2. **Arquitectura** (escalabilidad y mantenibilidad)
3. **Testing** (confiabilidad)

Una vez el backend esté profesionalizado, el frontend puede mejorarse incrementalmente sin romper funcionalidad.

**Siguiente paso sugerido:** Implementar Quick Wins (1 semana) y luego comenzar Fase 1.

¿Quieres que profundice en alguna sección específica o comenzamos con los Quick Wins?
