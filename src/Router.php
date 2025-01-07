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
                    $this->register($route->method, $route->path, [$controller, $method->getName()], $route->name);
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

    private function routeExists(string $requestMethod, string $path): bool
    {
        return isset($this->routes[$requestMethod][$path]);
    }

    private function register(string $requestMethod, string $path, array $action, string $name): self
    {
        if ($this->routeExists($requestMethod, $path)) {
            throw new \LogicException(
                "The method {$requestMethod} and path {$path} are already registered"
            );
        }
        if ($this->getPathByName($name) !== '') {
            throw new \LogicException(
                "Duplicated route name '{$name}' for {$requestMethod} and path {$path}"
            );
        }

        $this->routes[$requestMethod][$path] = [
            'action' => $action,
            'name' => $name
        ];
        return $this;
    }

    public function dispatch(Request $request): void
    {
        $path = preg_replace('#/index.php#', '', $request->getUri()->getPath());
        $path = $path !== '' ? $path : '/';

        $requestMethod = $request->getMethod();
        if ($this->routeExists($requestMethod, $path)) {
            $class = $this->routes[$requestMethod][$path]['action'][0];
            $method = $this->routes[$requestMethod][$path]['action'][1];
        } else {
            $class = NotFoundController::class;
            $method = 'index';
        }

        ob_start();
        $response = (new $class())->$method();
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
            foreach ($routePart as $routePath => $routeData) {
                if ($name === $routeData['name']) {
                    return $routePath;
                }
            }
        }
        return '';
    }
}