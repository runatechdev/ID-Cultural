<?php

namespace Backend\Repositories;

use PDO;

/**
 * BaseRepository - Clase abstracta con operaciones CRUD comunes
 * Todos los repositorios heredan de esta clase
 */
abstract class BaseRepository
{
    protected PDO $pdo;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Encuentra un registro por ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Encuentra un registro por campo específico
     */
    public function findBy(string $field, mixed $value): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$field} = ?");
        $stmt->execute([$value]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene todos los registros
     */
    public function all(array $conditions = [], int $limit = 1000, int $offset = 0): array
    {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $query .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    /**
     * Cuenta registros
     */
    public function count(array $conditions = []): int
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    /**
     * Crea un nuevo registro
     */
    public function create(array $data): array
    {
        // Eliminar campos no permitidos
        unset($data[$this->primaryKey]);

        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $query = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($data));

        $id = (int)$this->pdo->lastInsertId();
        return $this->find($id) ?? $data;
    }

    /**
     * Actualiza un registro existente
     */
    public function update(int $id, array $data): bool
    {
        // Eliminar campos no permitidos
        unset($data[$this->primaryKey]);

        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = ?";
        }

        $query = sprintf(
            "UPDATE %s SET %s WHERE {$this->primaryKey} = ?",
            $this->table,
            implode(', ', $fields)
        );

        $params = array_values($data);
        $params[] = $id;

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Elimina un registro
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Verifica si existe un registro
     */
    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() !== false;
    }

    /**
     * Ejecuta una query personalizada con parámetros
     */
    protected function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ejecuta una query que retorna un solo registro
     */
    protected function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Ejecuta una query de modificación (INSERT/UPDATE/DELETE)
     */
    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Inicia una transacción
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Confirma una transacción
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Revierte una transacción
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
