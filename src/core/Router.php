<?php

namespace App\Core;

use App\Core\Container;

class Router
{
    protected array $routes = [];
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $uri, string $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, string $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute(string $method, string $uri, string $action): void
    {
        $this->routes[$method][] = [
            'uri' => $uri,
            'pattern' => $this->convertToRegex($uri),
            'action' => $action
        ];
    }

    protected function convertToRegex(string $uri): string
    {
        return '#^' . preg_replace('#\{[^/]+\}#', '([^/]+)', $uri) . '$#';
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $parsedUrl = parse_url($requestUri);
        $path = $parsedUrl['path'] ?? '/';
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);

                [$controllerClass, $controllerMethod] = explode('@', $route['action']);

                $controller = $this->container->get($controllerClass);

                $args = array_merge($matches, [$queryParams]);

                call_user_func_array([$controller, $controllerMethod], $args);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
