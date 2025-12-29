<?php

namespace Backend\Repositories;

/**
 * ObraRepository - Maneja todas las operaciones de BD para obras/publicaciones
 */
class ObraRepository extends BaseRepository
{
    protected string $table = 'publicaciones';

    /**
     * Encuentra obras validadas con información del artista
     */
    public function findValidadasConArtista(int $limit = 50, int $offset = 0): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido,
                a.email as artista_email,
                a.provincia,
                a.municipio,
                a.status_perfil as artista_status
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.estado = 'validado'
            ORDER BY o.fecha_creacion DESC
            LIMIT ? OFFSET ?
        ";

        return $this->query($query, [$limit, $offset]);
    }

    /**
     * Encuentra obras de un artista específico
     */
    public function findByArtista(int $artistaId, ?string $estado = null): array
    {
        $query = "SELECT * FROM {$this->table} WHERE usuario_id = ?";
        $params = [$artistaId];

        if ($estado !== null) {
            $query .= " AND estado = ?";
            $params[] = $estado;
        }

        $query .= " ORDER BY fecha_creacion DESC";

        return $this->query($query, $params);
    }

    /**
     * Encuentra obras por categoría
     */
    public function findByCategoria(string $categoria, int $limit = 50): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.categoria = ? AND o.estado = 'validado'
            ORDER BY o.fecha_creacion DESC
            LIMIT ?
        ";

        return $this->query($query, [$categoria, $limit]);
    }

    /**
     * Busca obras por título o descripción
     */
    public function search(string $searchTerm, int $limit = 50): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.estado = 'validado'
            AND (
                o.titulo LIKE ? 
                OR o.descripcion LIKE ?
                OR a.nombre LIKE ?
                OR a.apellido LIKE ?
            )
            ORDER BY o.fecha_creacion DESC
            LIMIT ?
        ";

        $searchPattern = '%' . $searchTerm . '%';
        return $this->query($query, [
            $searchPattern,
            $searchPattern,
            $searchPattern,
            $searchPattern,
            $limit
        ]);
    }

    /**
     * Actualiza el estado de una obra
     */
    public function updateEstado(int $id, string $estado, ?string $motivo = null): bool
    {
        $data = [
            'estado' => $estado,
            'fecha_validacion' => date('Y-m-d H:i:s')
        ];

        if ($motivo !== null) {
            $data['motivo_rechazo'] = $motivo;
        }

        return $this->update($id, $data);
    }

    /**
     * Cuenta obras por estado
     */
    public function countByEstado(string $estado): int
    {
        return $this->count(['estado' => $estado]);
    }

    /**
     * Obtiene obras pendientes de validación
     */
    public function getPendientes(int $limit = 50, int $offset = 0): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido,
                a.email as artista_email
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.estado = 'pendiente'
            ORDER BY o.fecha_creacion ASC
            LIMIT ? OFFSET ?
        ";

        return $this->query($query, [$limit, $offset]);
    }

    /**
     * Obtiene estadísticas de obras
     */
    public function getStats(): array
    {
        $query = "
            SELECT 
                estado,
                COUNT(*) as total
            FROM {$this->table}
            GROUP BY estado
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
            $estado = $row['estado'];
            $total = (int)$row['total'];
            $stats[$estado] = $total;
            $stats['total'] += $total;
        }

        return $stats;
    }

    /**
     * Obtiene obras destacadas (últimas validadas)
     */
    public function getDestacadas(int $limit = 10): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido,
                a.provincia,
                a.municipio
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.estado = 'validado'
            ORDER BY o.fecha_creacion DESC
            LIMIT ?
        ";

        return $this->query($query, [$limit]);
    }

    /**
     * Obtiene obras por provincia
     */
    public function findByProvincia(string $provincia, int $limit = 50): array
    {
        $query = "
            SELECT 
                o.*,
                a.nombre as artista_nombre,
                a.apellido as artista_apellido,
                a.provincia,
                a.municipio
            FROM {$this->table} o
            INNER JOIN artistas a ON o.usuario_id = a.id
            WHERE o.estado = 'validado' AND a.provincia = ?
            ORDER BY o.fecha_creacion DESC
            LIMIT ?
        ";

        return $this->query($query, [$provincia, $limit]);
    }

    /**
     * Verifica si un artista tiene obras validadas
     */
    public function artistaTieneObrasValidadas(int $artistaId): bool
    {
        $query = "SELECT 1 FROM {$this->table} WHERE usuario_id = ? AND estado = 'validado' LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$artistaId]);
        
        return $stmt->fetch() !== false;
    }

    /**
     * Cuenta obras de un artista por estado
     */
    public function countByArtistaAndEstado(int $artistaId, string $estado): int
    {
        return $this->count([
            'usuario_id' => $artistaId,
            'estado' => $estado
        ]);
    }
}
