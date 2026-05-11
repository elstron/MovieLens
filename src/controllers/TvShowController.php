<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\TvShowService;

class TvShowController extends Controller
{
    private TvShowService $tvShowService;
    public function __construct(TvShowService $tvShowService)
    {
        $this->tvShowService = $tvShowService;
    }
    public function index(): void
    {
        $lastTvShowData = $this->tvShowService->getLastTvShow();
        $releasedTvShows = $this->tvShowService->getReleasedTvShows(5, 0);
        $lastTvShowsAdded = $this->tvShowService->getLastTvShowsAdded(5, 0);
        $this->twig('tvshows/index', [
            'title' => "Explora las Series",
            'lastTvShow' => $lastTvShowData,
            'releasedTvShows' => $releasedTvShows,
            'lastTvShowsAdded' => $lastTvShowsAdded
        ]);
    }
    public function show(string $slug): void
    {
        $tvShow = $this->tvShowService->getTvShowBySlug($slug);
        if (!$tvShow) {
            http_response_code(404);
            echo "Alto ahí rufian, no hay nada que ver aquí.";
            return;
        }
        $this->twig('tvshows/details', [
            'title' => $tvShow->title,
            'description' => $tvShow->synopsis,
            'tvShow' => $tvShow
        ]);
    }
    public function apiIndex(): void
    {
        // header('Content-Type: application/json');
        // echo json_encode($this->tvShowService->getAllTvShows(10, 0));
    }
}
