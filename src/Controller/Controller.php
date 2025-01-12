<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\App;
abstract class Controller
{
    protected ?array $urlParams;

    public function __construct()
    {
        include_once '../functions.php';
    }

    protected function getApp(): App
    {
        return App::getInstance();
    }

    protected function getUrlParam(string $name, mixed $default)
    {
        if (!isset($this->urlParams)) {
            $query = $this->getApp()
                ->getRequest()
                ->getUri()
                ->getQuery()
            ;
            parse_str($query, $this->urlParams);
        }

        return $this->urlParams[$name] ?? $default;
    }
}