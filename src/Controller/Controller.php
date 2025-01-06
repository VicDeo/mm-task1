<?php
declare(strict_types=1);

namespace Task1\Controller;

abstract class Controller
{
    public function __construct()
    {
        include_once '../functions.php';
    }
}