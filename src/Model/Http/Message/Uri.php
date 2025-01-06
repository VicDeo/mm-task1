<?php

declare(strict_types=1);

namespace Task1\Model\Http\Message;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    public function __construct(
        private string $path,
        private string $query
    ) {
    }

    public function getScheme(): string
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getAuthority(): string
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getUserInfo(): string
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getHost(): string
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getPort(): int|null
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withScheme($scheme): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withUserInfo(string $user, string|null $password = null): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withHost(string $host): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withPort(int|null $port): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withPath(string $path): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withQuery(string $query): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function withFragment(string $fragment): UriInterface
    {
        throw new \RuntimeException("Not implemented");
    }

    public function __toString(): string
    {
        throw new \RuntimeException("Not implemented");
    }
}