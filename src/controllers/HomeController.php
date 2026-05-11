<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TvShow;
use App\Services\MovieService;
use App\Services\TvShowService;

class HomeController extends Controller
{
    private MovieService $movieService;
    private TvShowService $tvShowService;

    public function __construct(MovieService $movieService, TvShowService $tvShowService)
    {
        $this->movieService = $movieService;
        $this->tvShowService = $tvShowService;
    }

    public function index(): void
    {
        $lastMovieData = $this->movieService->getLastMovie();
        $releasedMovies = $this->movieService->getReleasedMovies(5, 0);
        $lastMoviesAdded = $this->movieService->getLastMoviesAdded(5, 0);
        $lastTvShowsAdded = $this->tvShowService->getLastTvShowsAdded(5, 0);
        $this->twig(
            'index',
            [
                'title' => "Explora las Películas",
                'lastMovie' => $lastMovieData,
                'releasedMovies' => $releasedMovies,
                'lastMoviesAdded' => $lastMoviesAdded,
                'lastTvShowsAdded' => $lastTvShowsAdded
            ]
        );
    }
}
