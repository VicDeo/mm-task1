<?php

declare(strict_types=1);

namespace Task1\Model\Db;

interface TableInterface
{
    public function create(): void;

    public function truncate(): void;

    public function seed(): void;
}
