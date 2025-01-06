<?php
declare(strict_types=1);

namespace Task1;

class App
{
    private static App|null $app = null;

    private Config $config;

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


    public function run(): void
    {
        $this->config = new Config($this->baseDir);
        print($this->config->getValue('MYSQL_HOST'));
    }
}
