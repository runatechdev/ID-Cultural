<?php

namespace Backend\Repositories;

/**
 * ArtistaRepository - Maneja todas las operaciones de BD para artistas
 */
class ArtistaRepository extends BaseRepository
{
    protected string $table = 'artistas';

    /**
     * Encuentra artistas por estado (validado, pendiente, rechazado)
     */
    public function findByStatus(string $status, int $limit = 50, int $offset = 0): array
    {
        $query = "SELECT * FROM {$this->table} WHERE status_perfil = ? ORDER BY id DESC LIMIT ? OFFSET ?";
        return $this->query($query, [$status, $limit, $offset]);
    }

    /**
     * Obtiene artistas validados con filtros opcionales
     */
    public function findValidados(array $filters = []): array
    {
        $query = "SELECT * FROM {$this->table} WHERE status_perfil = :status";
        $params = ['status' => 'validado'];

        // Filtro por provincia
        if (isset($filters['provincia']) && !empty($filters['provincia'])) {
            $query .= " AND provincia = :provincia";
            $params['provincia'] = $filters['provincia'];
        }

        // Filtro por municipio
        if (isset($filters['municipio']) && !empty($filters['municipio'])) {
            $query .= " AND municipio = :municipio";
            $params['municipio'] = $filters['municipio'];
        }

        // Filtro por categoría
        if (isset($filters['categoria']) && !empty($filters['categoria'])) {
            $query .= " AND categoria = :categoria";
            $params['categoria'] = $filters['categoria'];
        }

        // Búsqueda por texto
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query .= " AND (nombre LIKE :search OR apellido LIKE :search OR biografia LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        // Ordenamiento
        $orderBy = $filters['order_by'] ?? 'id';
        $orderDir = strtoupper($filters['order_dir'] ?? 'DESC');
        $query .= " ORDER BY {$orderBy} {$orderDir}";

        // Paginación
        $limit = (int)($filters['limit'] ?? 50);
        $offset = (int)($filters['offset'] ?? 0);
        $query .= " LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }

    /**
     * Busca artista por email
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    /**
     * Actualiza el estado de un perfil
     */
    public function updateStatus(int $id, string $status, ?string $motivo = null): bool
    {
        $data = [
            'status_perfil' => $status,
            'fecha_validacion' => date('Y-m-d H:i:s')
        ];

        if ($motivo !== null) {
            $data['motivo_rechazo'] = $motivo;
        }

        return $this->update($id, $data);
    }

    /**
     * Cuenta artistas por estado
     */
    public function countByStatus(string $status): int
    {
        return $this->count(['status_perfil' => $status]);
    }

    /**
     * Obtiene artistas pendientes de validación
     */
    public function getPendientes(int $limit = 50, int $offset = 0): array
    {
        return $this->findByStatus('pendiente', $limit, $offset);
    }

    /**
     * Obtiene artistas con sus obras (JOIN)
     */
    public function findWithObras(int $artistaId): ?array
    {
        $query = "
            SELECT 
                a.*,
                COUNT(o.id) as total_obras
            FROM {$this->table} a
            LEFT JOIN publicaciones o ON o.usuario_id = a.id AND o.estado = 'validado'
            WHERE a.id = ?
            GROUP BY a.id
        ";

        return $this->queryOne($query, [$artistaId]);
    }

    /**
     * Busca artistas destacados (con más obras validadas)
     */
    public function findDestacados(int $limit = 10): array
    {
        $query = "
            SELECT 
                a.*,
                COUNT(o.id) as total_obras
            FROM {$this->table} a
            LEFT JOIN publicaciones o ON o.usuario_id = a.id AND o.estado = 'validado'
            WHERE a.status_perfil = 'validado'
            GROUP BY a.id
            HAVING total_obras > 0
            ORDER BY total_obras DESC, a.id DESC
            LIMIT ?
        ";

        return $this->query($query, [$limit]);
    }

    /**
     * Obtiene estadísticas de artistas
     */
    public function getStats(): array
    {
        $query = "
            SELECT 
                status_perfil,
                COUNT(*) as total
            FROM {$this->table}
            GROUP BY status_perfil
        ";

        $results = $this->query($query);
        
        $stats = [
            'total' => 0,
            'validado' => 0,
            'pendiente' => 0,
            'rechazado' => 0,
            'borrador' => 0
        ];

        foreach ($results as $row) {
            $status = $row['status_perfil'];
            $total = (int)$row['total'];
            $stats[$status] = $total;
            $stats['total'] += $total;
        }

        return $stats;
    }

    /**
     * Verifica si un email ya está registrado
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = "SELECT 1 FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId !== null) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetch() !== false;
    }

    /**
     * Busca artistas por rango de fechas
     */
    public function findByDateRange(string $startDate, string $endDate): array
    {
        $query = "
            SELECT * FROM {$this->table} 
            WHERE fecha_registro BETWEEN ? AND ?
            ORDER BY fecha_registro DESC
        ";

        return $this->query($query, [$startDate, $endDate]);
    }

    /**
     * Actualiza la última actividad del artista
     */
    public function touchLastActivity(int $id): bool
    {
        return $this->execute(
            "UPDATE {$this->table} SET ultima_actividad = NOW() WHERE id = ?",
            [$id]
        );
    }
}
