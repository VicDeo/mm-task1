<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message\Response;

use Task1\App;
use Task1\Model\Http\Message\Body;
use Psr\Http\Message\StreamInterface;
use Task1\Model\Http\Message\Response;

class Template extends Response
{

    public function __construct(
        protected string $templatePath,
        protected array $headers = [],
        protected string $protocolVersion = '1.1',
        protected int $statusCode = 200,
        protected string $reasonPhrase = ""
    ) {
    }

    public function getBody(): StreamInterface
    {
        $templateBase = App::getInstance()->getBaseDir() . "/view";
        $handle = fopen("{$templateBase}/{$this->templatePath}", "r");
        $this->body = new Body($handle);
        return $this->body;
    }
}
