<?php
declare(strict_types=1);

namespace Task1;

use Task1\Model\Http\Message\Response\Template;
use Task1\Model\Http\Message\Uri;
use Task1\Model\Http\Message\Body;
use Task1\Model\Http\Message\Request;
use Psr\Http\Message\RequestInterface;

class App
{
    private static App|null $app = null;

    private Config $config;

    private Request $request;

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

    public function run(): void
    {
        $this->config = new Config($this->baseDir);
        $request = $this->getRequest();
        $response = new Template(
             $this->getBaseDir() . "/view/page.php"
        );
        $responseHeader = "HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}";
        header(trim($responseHeader), true);

        echo (string) eval ("?>" . $response->getBody());
        print ($request->getUri()->getPath());
    }
}
