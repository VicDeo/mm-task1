<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    use MessageTrait;

    public function __construct(
        private string $method,
        private UriInterface $uri,
        private array $headers,
        private StreamInterface $body,
        private string $protocolVersion
    ) {
    }

    public function getRequestTarget(): string
    {
        $path = $this->uri->getPath();
        $target = $path !== '' ? $path : '/';

        $query = $this->uri->getQuery();
        if ($query !== '') {
            $target = "{$target}?{$query}";
        }

        return $target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): RequestInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        throw new \RuntimeException("Not implemented");
    }
}
