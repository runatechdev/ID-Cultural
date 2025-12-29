# 📦 Repository Pattern - Implementado

**Fecha:** 29 de diciembre de 2025  
**Estado:** ✅ Completado y testeado

---

## 🎯 Objetivo

Separar la lógica de acceso a datos (SQL) de los Controllers, creando una capa de abstracción que mejora la mantenibilidad, testabilidad y organización del código.

---

## 📁 Estructura Creada

```
backend/
  repositories/
    ├── BaseRepository.php       ← Clase abstracta con CRUD común
    ├── ArtistaRepository.php    ← Operaciones específicas de artistas
    ├── ObraRepository.php       ← Operaciones específicas de obras/publicaciones
    └── NoticiaRepository.php    ← Operaciones específicas de noticias
```

---

## ✨ Componentes Implementados

### 1. **BaseRepository** (Abstracto)

Métodos CRUD genéricos que todos los repositorios heredan:

```php
// Búsqueda
find(int $id): ?array                          // Por ID
findBy(string $field, mixed $value): ?array    // Por campo
all(array $conditions, int $limit): array      // Todos con filtros
exists(int $id): bool                          // Verificar existencia

// Conteo
count(array $conditions): int                  // Contar registros

// Escritura
create(array $data): array                     // Crear registro
update(int $id, array $data): bool             // Actualizar
delete(int $id): bool                          // Eliminar

// Transacciones
beginTransaction(), commit(), rollback()

// Query Helpers (protected)
query(string $sql, array $params): array       // Múltiples registros
queryOne(string $sql, array $params): ?array   // Un registro
execute(string $sql, array $params): bool      // Modificación
```

**Ventajas:**
- ✅ Elimina SQL repetitivo en controllers
- ✅ Prepared statements nativos (seguridad)
- ✅ Consistencia en toda la aplicación
- ✅ Facilita testing (mock repositories)

---

### 2. **ArtistaRepository**

Métodos específicos del dominio de artistas:

```php
// Por estado de perfil
findByStatus(string $status): array            // pendiente/validado/rechazado
findValidados(array $filters): array           // Con filtros (provincia, municipio, búsqueda)
getPendientes(): array                         // Pendientes de validación

// Estadísticas
getStats(): array                              // Totales por estado
countByStatus(string $status): int             // Contar por estado

// Búsquedas especializadas
findByEmail(string $email): ?array             // Por email
findDestacados(int $limit): array              // Con más obras validadas
findWithObras(int $id): ?array                 // Con JOIN de publicaciones

// Operaciones
updateStatus(int $id, string $status): bool    // Cambiar estado
emailExists(string $email): bool               // Verificar email único
touchLastActivity(int $id): bool               // Actualizar timestamp
```

**Uso en Controller:**
```php
// ANTES (SQL directo)
$stmt = $this->pdo->query("SELECT COUNT(*) FROM artistas WHERE status='validado'");
$total = $stmt->fetchColumn();

// AHORA (Repository)
$stats = $this->artistaRepo->getStats();
$total = $stats['validado'];
```

---

### 3. **ObraRepository** (para tabla `publicaciones`)

Métodos especializados en obras/publicaciones:

```php
// Por estado
findValidadasConArtista(): array               // Con JOIN de artista
getPendientes(): array                         // Pendientes de validación
getDestacadas(int $limit): array               // Últimas validadas

// Búsquedas
findByArtista(int $artistaId): array           // De un artista
findByCategoria(string $categoria): array      // Por categoría
findByProvincia(string $provincia): array      // Por provincia del artista
search(string $term): array                    // Búsqueda full-text

// Estadísticas
getStats(): array                              // Totales por estado
countByEstado(string $estado): int             // Contar por estado
artistaTieneObrasValidadas(int $id): bool      // Verificación

// Operaciones
updateEstado(int $id, string $estado): bool    // Cambiar estado
```

**Nota importante:** La tabla se llama `publicaciones` (no `obras`) y usa `usuario_id` (no `artista_id`).

---

### 4. **NoticiaRepository**

Métodos para gestión de noticias:

```php
// Búsqueda
findAll(): array                               // Todas con editor
findWithEditor(int $id): ?array                // Por ID con JOIN
search(string $term): array                    // Por título/contenido

// Operaciones
getRecientes(int $limit): array                // Últimas N noticias
findByEditor(int $editorId): array             // De un editor

// Estadísticas
getStats(): array                              // Total de noticias
```

**Nota:** La tabla `noticias` tiene estructura simple (sin `estado`, usa `editor_id` como FK a artistas).

---

## 🧪 Testing

Creado script de pruebas en `scripts/test-repositories.php`:

```bash
docker exec idcultural_web php scripts/test-repositories.php
```

### Resultados del test:

```
✅ ArtistaRepository
   - getStats(): 15 validados, 0 pendientes
   - findValidados(5): 5 artistas
   - countByStatus('validado'): 15

✅ ObraRepository
   - getStats(): 21 validadas, 0 pendientes
   - findValidadasConArtista(5): 5 obras con artista

✅ NoticiaRepository
   - getStats(): 6 noticias totales
   - findAll(5): 5 noticias con editor

✅ BaseRepository CRUD
   - count(): 15 artistas
   - exists(1): verificación de existencia
```

---

## 📊 Refactorización de Controllers

### ArtistaController - PARCIALMENTE REFACTORIZADO

**Métodos actualizados:**

```php
// ✅ getProfiles() - Ahora usa $this->artistaRepo->findByStatus()
// ✅ getStats() - Ahora usa $this->artistaRepo->getStats()
```

**Pendiente de refactorizar:** 
- `getAll()`, `getOne()`, `register()`, `updatePersonalProfile()`, `validateProfile()`, etc.

---

## 🔄 Patrón de Migración

**Proceso para refactorizar un controller:**

1. **Inyectar Repository:**
   ```php
   private ArtistaRepository $artistaRepo;
   
   public function __construct() {
       global $pdo;
       $this->artistaRepo = new ArtistaRepository($pdo);
   }
   ```

2. **Reemplazar SQL directo:**
   ```php
   // ANTES
   $stmt = $this->pdo->prepare("SELECT * FROM artistas WHERE id = ?");
   $stmt->execute([$id]);
   $artista = $stmt->fetch();
   
   // DESPUÉS
   $artista = $this->artistaRepo->find($id);
   ```

3. **Usar métodos especializados:**
   ```php
   // Búsqueda compleja con filtros
   $artistas = $this->artistaRepo->findValidados([
       'provincia' => $_GET['provincia'],
       'search' => $_GET['q'],
       'limit' => 50
   ]);
   ```

---

## 📈 Beneficios Obtenidos

### ✅ Separación de Responsabilidades
- Controllers: Validación de entrada, lógica de negocio, respuestas HTTP
- Repositories: Acceso a datos, queries SQL, persistencia

### ✅ Reutilización de Código
```php
// Método getStats() usado en múltiples endpoints:
$statsArtistas = $this->artistaRepo->getStats();   // API Admin
$statsObras = $this->obraRepo->getStats();         // Dashboard
$statsNoticias = $this->noticiaRepo->getStats();   // Analíticas
```

### ✅ Testabilidad
```php
// En PHPUnit (futuro):
$mockRepo = $this->createMock(ArtistaRepository::class);
$mockRepo->method('find')->willReturn(['id' => 1, 'nombre' => 'Test']);
$controller = new ArtistaController($mockRepo);
```

### ✅ Mantenibilidad
- Un cambio en la estructura de `artistas` solo afecta `ArtistaRepository`
- No hay que buscar SQL en 15 archivos diferentes
- Documentación centralizada (PHPDoc en repositorios)

### ✅ Seguridad
- Prepared statements en todos los queries (BaseRepository)
- Validación de tipos (PHP 8.2 type hints)
- No hay SQL injection possible

---

## 🚀 Próximos Pasos

### 1. **Completar refactorización de Controllers**
- [ ] ArtistaController (80% pendiente)
- [ ] ObraController (100% pendiente)
- [ ] NoticiaController (100% pendiente)
- [ ] AdminController (100% pendiente)

### 2. **Dependency Injection Container**
```php
// Eliminar global $pdo, usar DI:
$container->set(ArtistaRepository::class, function($c) {
    return new ArtistaRepository($c->get(PDO::class));
});
```

### 3. **Service Layer**
```php
// Lógica de negocio fuera de controllers:
class ArtistaService {
    public function validarPerfil(int $id, string $status) {
        // 1. Verificar permisos
        // 2. Actualizar artista (repo)
        // 3. Enviar notificación
        // 4. Log de auditoría
    }
}
```

### 4. **Unit Tests con PHPUnit**
```php
class ArtistaRepositoryTest extends TestCase {
    public function testFindReturnsArtistaById() { ... }
    public function testFindByEmailReturnsNullWhenNotExists() { ... }
}
```

---

## 📚 Convenciones y Best Practices

### Naming
- Repositorios: `{Entity}Repository.php` (singular)
- Tabla en plural: `protected string $table = 'artistas';`
- Métodos descriptivos: `findValidados()` no `get()` ni `list()`

### Return Types
- Un registro: `?array` (nullable)
- Múltiples: `array` (puede estar vacío)
- Booleanos: `bool` (operaciones CRUD)

### SQL Safety
- ✅ Siempre usar prepared statements
- ✅ Validar tipos en métodos (PHP type hints)
- ❌ Nunca concatenar strings en SQL

### Transacciones
```php
$this->artistaRepo->beginTransaction();
try {
    $this->artistaRepo->updateStatus($id, 'validado');
    $this->obraRepo->updateEstado($obraId, 'validado');
    $this->artistaRepo->commit();
} catch (Exception $e) {
    $this->artistaRepo->rollback();
    throw $e;
}
```

---

## 🎓 Recursos Adicionales

- [Repository Pattern - Martin Fowler](https://martinfowler.com/eaaCatalog/repository.html)
- [PHP The Right Way - Databases](https://phptherightway.com/#databases)
- [Doctrine ORM](https://www.doctrine-project.org/) (inspiración del patrón)

---

## ✅ Checklist de Implementación

- [x] BaseRepository con CRUD común
- [x] ArtistaRepository con métodos especializados
- [x] ObraRepository adaptado a `publicaciones`
- [x] NoticiaRepository simplificado
- [x] Script de testing funcional
- [x] Refactorización inicial de ArtistaController (2 métodos)
- [ ] Refactorización completa de todos los controllers
- [ ] Dependency Injection Container
- [ ] Service Layer
- [ ] PHPUnit Tests

---

**Progreso Backend:** 🟢🟢🟢⚪⚪ (40% → 50%)

**Próximo paso:** Dependency Injection Container (PHP-DI)
