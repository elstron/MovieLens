<?php

namespace App\Core;

use App\Utils\Minify;

class View
{
    public function render(string $template, array $data = []): void
    {
        $basePath = null;
        foreach ([__DIR__ . '/../views', __DIR__ . '/../Views'] as $candidate) {
            if (is_dir($candidate)) {
                $basePath = $candidate;
                break;
            }
        }
        $path = ($basePath ?: __DIR__ . '/../views') . '/' . $template . '.php';
        if (!empty($data)) {
            extract($data);
        }
        if (!file_exists($path)) {
            http_response_code(404);
            return;
        } else {
            ob_start();
            require $path;
            $output = ob_get_clean();
            echo Minify::html($output);
        }
    }
}
