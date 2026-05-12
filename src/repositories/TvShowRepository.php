<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\TvShow;

class TvShowRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?TvShow
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM series WHERE ID_serie = :id LIMIT 1");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function findBySlug(string $slug): ?TvShow
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM series WHERE slug_serie = :slug LIMIT 1");
        $stmt->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? TvShow::fromArray($data) : null;
    }

    public function findAllPaginated(int $limit, int $offset): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM series ORDER BY ID_serie DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function findByCriteria(
        array $criteria = [],
        ?int $limit = null,
        ?int $offset = null,
        ?string $orderBy = null,
        string $orderDir = 'DESC'
    ): array {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM series";
        $where = [];
        $params = [];

        foreach ($criteria as $key => $value) {
            // Soporta operadores en la clave, ej: 'release_date >='
            if (preg_match('/^(\w+)\s*(>=|<=|<>|!=|=|>|<)$/', $key, $matches)) {
                $field = $matches[1];
                $operator = $matches[2];
            } else {
                $field = $key;
                $operator = '=';
            }
            $paramKey = ':' . preg_replace('/\W/', '_', $field . uniqid());
            $where[] = "$field $operator $paramKey";
            $params[$paramKey] = $value;
        }

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy $orderDir";
        }

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = $limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
            $params[':offset'] = $offset;
        }
        $stmt = $conn->prepare($sql);

        foreach ($params as $key => $value) {
            $type = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();
        $tvShowsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $tvShows = [];
        foreach ($tvShowsData as $data) {
            $tvShows[] = TvShow::fromArray($data);
        }
        return $tvShows;
    }
}

