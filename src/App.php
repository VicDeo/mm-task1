<?php
declare(strict_types=1);

namespace Task1;

use Task1\Model\Db\TableInterface;
use Task1\Model\Http\Message\Uri;
use Task1\Model\Http\Message\Body;
use Task1\Model\Http\Message\Request;
use Psr\Http\Message\RequestInterface;

class App
{
    private static App|null $app = null;

    private Config $config;

    private Request $request;

    private Router $router;

    private function __construct(private string $baseDir)
    {
    }

    public static function getInstance(): App
    {
        if (self::$app === null) {
            $baseDir = __DIR__;
            self::$app = new App($baseDir);
        }
        return self::$app;
    }

    public function getBaseDir(): string
    {
        return $this->baseDir;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getDbTable(string $modelName): TableInterface
    {
        $fullClassName = "Task1\\Model\\Db\\Table\\{$modelName}";
        if (class_exists($fullClassName)){
            return new $fullClassName(
                $this->config->getValue('MYSQL_HOST'),
                $this->config->getValue('MYSQL_DATABASE'),
                $this->config->getValue('MYSQL_USER'),
                $this->config->getValue('MYSQL_PASSWORD')
            );
        }
        throw new \RuntimeException("Database model '$modelName' not found");
    }

    public function getRequest(): RequestInterface
    {
        if (!isset($this->request)) {
            preg_match('/\d+\.\d+$/', $_SERVER['SERVER_PROTOCOL'], $protocolMatches);
            $serverProtocol = $protocolMatches[0];

            $body = new Body(fopen('php://input', 'r'));
            $uri = new Uri(
                $_SERVER['REQUEST_URI'],
                $_SERVER['QUERY_STRING']
            );
            $this->request = new Request(
                $_SERVER['REQUEST_METHOD'],
                $uri,
                $_SERVER,
                $body,
                $serverProtocol
            );
        }
        return $this->request;
    }

    public function getRouter(): Router
    {
        if (!isset($this->router)) {
            $this->router = new Router();
        }
        return $this->router;
    }

    public function run(): void
    {
        $this->config = new Config($this->baseDir);
        $request = $this->getRequest();
        $this->getRouter()
            ->registerRoutes()
            ->dispatch($request);
    }
}
