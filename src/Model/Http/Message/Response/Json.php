<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message\Response;

use Task1\Model\Http\Message\Body;
use Psr\Http\Message\StreamInterface;
use Task1\Model\Http\Message\Response;

class Json extends Response
{
    private const array DEFAULT_HEADERS = [
        'Content-Type' => ['application/json']
    ]; 

    public function __construct(
        protected array $data,
        protected array $headers = self::DEFAULT_HEADERS,
        protected string $protocolVersion = '1.1',
        protected int $statusCode = 200,
        protected string $reasonPhrase = ""
    ) {
    }

    public function getBody(): StreamInterface
    {
        $stringResponse = json_encode($this->data);
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $stringResponse);
        rewind($stream);
        $this->body = new Body($stream);
        return $this->body;
    }
}
