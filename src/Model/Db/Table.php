<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;

abstract class Table extends Db implements TableInterface
{
    public function init(): void
    {
        $statement = $this->prepare(static::CREATE_STATEMENT);
        $statement->execute();
    }

    public function truncate(): void
    {
        $statement = $this->prepare("TRUNCATE TABLE `{$this->getTableName()}`");
        $statement->execute();
    }
}
