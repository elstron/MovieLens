<?php

namespace App\Services;

use App\Models\TvShow;
use App\Repositories\TvShowRepository;

class TvShowService
{
    private TvShowRepository $tvShowRepository;

    public function __construct(TvShowRepository $tvShowRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
    }

    public function getAllTvShows(int $limit, int $offset): array
    {
        return $this->tvShowRepository->findAllPaginated($limit, $offset);
    }

    public function getTvShowById(int $id): ?TvShow
    {
        return $this->tvShowRepository->findById($id);
    }

    public function getTvShowBySlug(string $slug): ?TvShow
    {
        return $this->tvShowRepository->findBySlug($slug);
    }

    public function getLastTvShow(): ?TvShow
    {
        $limit = 1;
        $offset = 0;
        return $this->tvShowRepository->findByCriteria(
            [],
            $limit,
            $offset,
            'ID_serie',
            'DESC'
        )[0] ?? null;
    }

    public function getLastTvShowsAdded(int $limit = 5, int $offset = 0): array
    {
        return $this->tvShowRepository->findByCriteria([], $limit, $offset, 'ID_serie', 'DESC');
    }

    public function getReleasedTvShows(int $limit, int $offset): array
    {
        $criteria = [
            'date_serie >=' => date('Y-m-d', strtotime('-30 days'))
        ];
        return $this->tvShowRepository->findByCriteria($criteria, $limit, $offset, 'date_serie', 'DESC');
    }
}
