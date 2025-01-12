<?php
declare(strict_types=1);

namespace Task1;

use Psr\Http\Message\ResponseInterface;
use Task1\Controller\NotFoundController;
use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Request;

class Router
{
    private const string CONTROLLERS_NAMESPACE = 'Task1\Controller';
    private array $routes = [];

    public function registerRoutes(): Router
    {
        foreach ($this->getControllers() as $controller) {
            $reflectionController = new \ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $route->controllerClass = $controller;
                    $route->action = $method->getName();
                    $this->register($route);
                }
            }
        }
        return $this;
    }

    private function getControllers(): array
    {
        $namespace = self::CONTROLLERS_NAMESPACE;
        $controllersDirectory = App::getInstance()->getBaseDir() . "/Controller";
        $files = scandir($controllersDirectory);

        $classes = array_map(function ($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        $controllers = array_filter($classes, function ($possibleClass) {
            return class_exists($possibleClass);
        });

        return $controllers;
    }

    private function routeExists(Route $route): bool
    {
        return isset($this->routes[$route->method][$route->path]);
    }

    private function register(route $route): self
    {
        if ($this->routeExists($route)) {
            throw new \LogicException(
                "The method {$route->method} and path {$route->path} are already registered"
            );
        }

        if ($this->getPathByName($route->name) !== '') {
            throw new \LogicException(
                "Duplicated route name '{$route->name}' for {$route->method} and path {$route->path}"
            );
        }

        $this->routes[$route->method][$route->path] = $route;

        return $this;
    }

    public function dispatch(Request $request): void
    {
        $path = preg_replace('#^/index.php#', '', $request->getUri()->getPath());
        $path = $path !== '' ? $path : '/';

        // Remove query string form path
        $query = $request->getUri()->getQuery();
        if ($query !== '') {
            $escapedQuery = preg_quote("?$query", '#');
            $path = preg_replace("#$escapedQuery$#", '', $path);
        }

        $requestMethod = $request->getMethod();

        $dispatchedRoute = new Route(
            $requestMethod,
            $path,
            '',
            NotFoundController::class,
            'index'
        );
        if ($this->routeExists($dispatchedRoute)) {
            $dispatchedRoute = $this->routes[$requestMethod][$path];
        }

        ob_start();
        $response = $dispatchedRoute->getResponse();
        if ($response instanceof ResponseInterface) {
            $responseHeader = "HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}";
            header(trim($responseHeader), true);
            if ($response->hasHeader('Content-Type')) {
                $contentType = $response->getHeaderLine('Content-Type');
            } else {
                $contentType = "text/html; charset=utf-8";
            }
            header("Content-Type: {$contentType}");
            echo (string) eval ("?>" . $response->getBody());
        } else {
            // Response is a string
            echo $response;
        }
        ob_end_flush();
    }

    public function getPathByName(string $name): string
    {
        foreach ($this->routes as $routePart) {
            foreach ($routePart as $route) {
                if ($name === $route->name) {
                    return $route->path;
                }
            }
        }
        return '';
    }
}