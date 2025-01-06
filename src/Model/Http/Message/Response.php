<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    use MessageTrait;

    public function __construct(
        protected array $headers,
        protected StreamInterface $body,
        protected string $protocolVersion,
        protected int $statusCode,
        protected string $reasonPhrase
    ) {
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }
}