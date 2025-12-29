<?php

namespace Backend\Repositories;

/**
 * NoticiaRepository - Maneja todas las operaciones de BD para noticias
 */
class NoticiaRepository extends BaseRepository
{
    protected string $table = 'noticias';

    /**
     * Obtiene todas las noticias ordenadas por fecha
     */
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $query = "
            SELECT 
                n.*,
                a.nombre as editor_nombre,
                a.apellido as editor_apellido
            FROM {$this->table} n
            LEFT JOIN artistas a ON n.editor_id = a.id
            ORDER BY n.fecha_creacion DESC
            LIMIT ? OFFSET ?
        ";

        return $this->query($query, [$limit, $offset]);
    }

    /**
     * Obtiene una noticia por ID con información del editor
     */
    public function findWithEditor(int $id): ?array
    {
        $query = "
            SELECT 
                n.*,
                a.nombre as editor_nombre,
                a.apellido as editor_apellido,
                a.foto_perfil as editor_foto
            FROM {$this->table} n
            LEFT JOIN artistas a ON n.editor_id = a.id
            WHERE n.id = ?
        ";

        return $this->queryOne($query, [$id]);
    }

    /**
     * Busca noticias por título o contenido
     */
    public function search(string $searchTerm, int $limit = 50): array
    {
        $query = "
            SELECT 
                n.*,
                a.nombre as editor_nombre,
                a.apellido as editor_apellido
            FROM {$this->table} n
            LEFT JOIN artistas a ON n.editor_id = a.id
            WHERE n.titulo LIKE ? OR n.contenido LIKE ?
            ORDER BY n.fecha_creacion DESC
            LIMIT ?
        ";

        $searchPattern = '%' . $searchTerm . '%';
        return $this->query($query, [$searchPattern, $searchPattern, $limit]);
    }

    /**
     * Obtiene las últimas noticias
     */
    public function getRecientes(int $limit = 10): array
    {
        return $this->findAll($limit, 0);
    }

    /**
     * Obtiene estadísticas de noticias
     */
    public function getStats(): array
    {
        $total = $this->count();
        
        return [
            'total' => $total
        ];
    }

    /**
     * Obtiene noticias de un editor
     */
    public function findByEditor(int $editorId, int $limit = 50): array
    {
        $query = "
            SELECT * FROM {$this->table}
            WHERE editor_id = ?
            ORDER BY fecha_creacion DESC
            LIMIT ?
        ";

        return $this->query($query, [$editorId, $limit]);
    }
}
