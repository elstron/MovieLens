<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        (new View())->render($template, $data);
    }
    protected function twig(string $template, array $data = []): void
    {
        (new TwigView())->render($template, $data);
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
