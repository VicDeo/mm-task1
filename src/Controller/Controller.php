<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\App;
abstract class Controller
{
    public function __construct()
    {
        include_once '../functions.php';
    }

    protected function getApp(): App
    {
        return App::getInstance();
    }
}