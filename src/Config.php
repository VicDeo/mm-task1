<?php
declare(strict_types=1);

namespace Task1;

class Config
{
    private array $config;

    public function __construct(string $baseDir)
    {
        $configPath = "{$baseDir}/.env";
        $this->config = parse_ini_file($configPath);
        if ($this->config === false) {
            throw new \RuntimeException("Bad config file format");
        }
    }

    public function getValue(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }
}
