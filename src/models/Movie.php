<?php

namespace App\Models;

class Movie
{
    public ?int $id;
    public string $title;
    public string $originalTitle;
    public string $slug;
    public string $rating;
    public string $duration;
    public string $coverImage;
    public string $posterImage;
    public string $genres;
    public string $synopsis;
    public string $links;
    public string $seoTitle;
    public string $seoDescription;
    public ?string $network;
    public string $downloadLink;
    public string $movieDate;
    public string $dateMovie;
    public string $updatedAt;

    public function __construct(
        ?int $id = null,
        string $title = '',
        string $originalTitle = '',
        string $slug = '',
        string $rating = '',
        string $duration = '',
        string $coverImage = '',
        string $posterImage = '',
        string $genres = '',
        string $synopsis = '',
        string $links = '',
        string $seoTitle = '',
        string $seoDescription = '',
        ?string $network = null,
        string $downloadLink = '',
        string $movieDate = '',
        string $dateMovie = '',
        string $updatedAt = ''
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->originalTitle = $originalTitle;
        $this->slug = $slug;
        $this->rating = $rating;
        $this->duration = $duration;
        $this->coverImage = $coverImage;
        $this->posterImage = $posterImage;
        $this->genres = $genres;
        $this->synopsis = $synopsis;
        $this->links = $links;
        $this->seoTitle = $seoTitle;
        $this->seoDescription = $seoDescription;
        $this->network = $network;
        $this->downloadLink = $downloadLink;
        $this->movieDate = $movieDate;
        $this->dateMovie = $dateMovie;
        $this->updatedAt = $updatedAt;
    }

    /**
     * Crea una instancia de Movie desde un array de datos de la base de datos.
     * Esto ayuda a mapear directamente las columnas de la DB a propiedades del objeto.
     * @param array $data Array asociativo con los datos de la fila de la DB.
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['ID_movie'] ?? null,
            $data['titulo_movie'] ?? '',
            $data['original_titulo_movie'] ?? '',
            $data['slug_movie'] ?? '',
            $data['calificacion_movie'] ?? '',
            $data['duracion'] ?? '',
            $data['portada_movie'] ?? '',
            $data['poster_movie_2'] ?? '',
            $data['generos_movie'] ?? '',
            $data['sinopsis_movie'] ?? '',
            $data['enlaces_movie'] ?? '',
            $data['titulo_seo'] ?? '',
            $data['descripcion_seo'] ?? '',
            $data['network'] ?? null,
            $data['download'] ?? '',
            $data['movieDate'] ?? '',
            $data['date_movie'] ?? '',
            $data['up_date'] ?? ''
        );
    }

    /**
     * Convierte la instancia de Movie en un array para ser usado en operaciones de DB.
     * Los nombres de las claves del array coinciden con los nombres de las columnas de la DB.
     * @return array
     */
    public function toArray(): array
    {
        return [
            'ID_movie' => $this->id,
            'titulo_movie' => $this->title,
            'original_titulo_movie' => $this->originalTitle,
            'slug_movie' => $this->slug,
            'calificacion_movie' => $this->rating,
            'duracion' => $this->duration,
            'portada_movie' => $this->coverImage,
            'poster_movie_2' => $this->posterImage,
            'generos_movie' => $this->genres,
            'sinopsis_movie' => $this->synopsis,
            'enlaces_movie' => $this->links,
            'titulo_seo' => $this->seoTitle,
            'descripcion_seo' => $this->seoDescription,
            'network' => $this->network,
            'download' => $this->downloadLink,
            'movieDate' => $this->movieDate,
            'date_movie' => $this->dateMovie,
            'up_date' => $this->updatedAt,
        ];
    }

    public function hasNetwork(): bool
    {
        return !empty($this->network);
    }
}

