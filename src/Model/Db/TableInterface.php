<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use PDO;

interface TableInterface
{
    public function init(): void;

    public function truncate(): void;
}
