<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Utils\Minify;

class TwigView
{
    protected Environment $twig;

    public function __construct()
    {
        $appEnv = getenv('APP_ENV') ?: 'dev';
        $isProd = $appEnv === 'prod';
        $viewPaths = array_values(array_filter([
            __DIR__ . '/../views',
            __DIR__ . '/../Views',
        ], 'is_dir'));
        $loader = new FilesystemLoader($viewPaths ?: [__DIR__ . '/../views']);
        $this->twig = new Environment($loader, [
            'cache' => $isProd ? __DIR__ . '/../cache/twig' : false,
            'auto_reload' => !$isProd,
        ]);
    }

    public function render(string $template, array $data = []): void
    {
        ob_start();
        $output = $this->twig->render($template . '.twig', $data);
        echo Minify::html($output);
    }
}
