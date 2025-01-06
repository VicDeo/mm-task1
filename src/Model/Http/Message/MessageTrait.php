<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\MessageInterface;

Trait MessageTrait
{
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        $lowerName = strtolower($name);
        foreach ($this->headers as $headerName => $headerValue) {
            if (strtolower($headerName) === $lowerName) {
                return true;
            }
        }
        return false;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withoutHeader(string $name): MessageInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getHeader(string $name): array
    {
        $lowerName = strtolower($name);
        foreach ($this->headers as $headerName => $headerValue) {
            if (strtolower($headerName) === $lowerName) {
                return $headerValue;
            }
        }
        return [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        throw new \RuntimeException("Not implemented");
    }    
}