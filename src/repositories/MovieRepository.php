<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Movie;

class MovieRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Encuentra una película por su ID.
     * @param int $id El ID de la película.
     * @return Movie|null La película encontrada o null si no existe.
     */
    public function findById(int $id): ?Movie
    {
        $conn = $this->db->getConnection(); // Obtén la conexión PDO
        $stmt = $conn->prepare("SELECT * FROM peliculas WHERE ID_movie = :id LIMIT 1");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? Movie::fromArray($data) : null;
    }

    /**
     * Encuentra una película por su slug.
     * @param string $slug El slug de la película.
     * @return Movie|null La película encontrada o null si no existe.
     */
    public function findBySlug(string $slug): ?Movie
    {
        $conn = $this->db->getConnection(); // Obtén la conexión PDO
        $stmt = $conn->prepare("SELECT * FROM peliculas WHERE slug_movie = :slug LIMIT 1"); // Asumiendo 'slug_movie' en tu DB
        $stmt->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? Movie::fromArray($data) : null;
    }

    /**
     * Obtiene una lista paginada de películas.
     * @param int $limit El número máximo de películas a devolver.
     * @param int $offset El desplazamiento para la paginación.
     * @return Movie[] Un array de objetos Movie.
     */
    public function findAllPaginated(int $limit, int $offset): array
    {
        $conn = $this->db->getConnection(); // Obtén la conexión PDO
        $stmt = $conn->prepare("SELECT * FROM peliculas ORDER BY movieDate DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $moviesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $movies = [];
        foreach ($moviesData as $data) {
            $movies[] = Movie::fromArray($data);
        }
        return $movies;
    }

    /**
     * Encuentra la última película.
     * @return Movie|null La última película o null si no existe.
     */
    public function findLast(): ?Movie
    {
        $conn = $this->db->getConnection(); // Obtén la conexión PDO
        $stmt = $conn->prepare("SELECT * FROM peliculas ORDER BY movieDate DESC LIMIT 1");
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? Movie::fromArray($data) : null;
    }

    /**
     * Guarda una película (inserta o actualiza).
     * @param Movie $movie El objeto Movie a guardar.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function save(Movie $movie): bool
    {
        $conn = $this->db->getConnection();
        $data = $movie->toArray();

        if ($movie->id) {
            $stmt = $conn->prepare("UPDATE peliculas SET title_movie = :title, slug_movie = :slug, sinopsis_movie = :description, release_date = :releaseDate WHERE ID_movie = :id");
            $stmt->bindParam(':id', $movie->id, \PDO::PARAM_INT);
        } else {
            $stmt = $conn->prepare("INSERT INTO peliculas (title_movie, slug_movie, sinopsis_movie, release_date) VALUES (:title, :slug, :description, :releaseDate)");
        }

        $stmt->bindParam(':title', $data['title_movie'], \PDO::PARAM_STR);
        $stmt->bindParam(':slug', $data['slug_movie'], \PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['sinopsis_movie'], \PDO::PARAM_STR);
        $stmt->bindParam(':releaseDate', $data['release_date'], \PDO::PARAM_STR);

        $result = $stmt->execute();

        if (!$movie->id && $result) {
            $movie->id = (int)$conn->lastInsertId();
        }

        return $result;
    }

    /**
     * Elimina una película por su ID.
     * @param int $id El ID de la película a eliminar.
     * @return bool True si la eliminación fue exitosa, false en caso contrario.
     */
    public function delete(int $id): bool
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM peliculas WHERE ID_movie = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca películas según criterios dinámicos.
     * @param array $criteria ['campo' => valor, ...]
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $orderBy
     * @param string $orderDir
     * @return Movie[]
     */
    public function findByCriteria(
        array $criteria = [],
        ?int $limit = null,
        ?int $offset = null,
        ?string $orderBy = null,
        string $orderDir = 'DESC'
    ): array {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM peliculas";
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
        $moviesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $movies = [];
        foreach ($moviesData as $data) {
            $movies[] = Movie::fromArray($data);
        }
        return $movies;
    }
}
