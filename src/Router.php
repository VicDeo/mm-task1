<?php
declare(strict_types=1);

namespace Task1;

use Psr\Http\Message\ResponseInterface;
use Task1\Controller\NotFoundController;
use Task1\Model\Http\Message\Request;

class Router
{
    private const string CONTROLLERS_NAMESPACE = 'Task1\Controller';
    private array $routes = [];

    public function registerRoutes(): Router
    {
        return $this;
    }

    public function dispatch(Request $request): void
    {
        $path = preg_replace('#/index.php#', '', $request->getUri()->getPath());
        $path = $path !== '' ? $path : '/';

        $requestMethod = $request->getMethod();
        $class = NotFoundController::class;
        $method = 'index';

        ob_start();
        $response = (new $class())->$method();
        if ($response instanceof ResponseInterface) {
            $responseHeader = "HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}";
            header(trim($responseHeader), true);

            echo (string) eval ("?>" . $response->getBody());
        } else {
            // Response is a string
            echo $response;
        }
        ob_end_flush();
    }
}