<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;

abstract class Table extends Db implements TableInterface
{
    public function create(): void
    {
        $statement = $this->prepare(static::CREATE_STATEMENT);
        $statement->execute();
    }

    public function seed(): void
    {
        $statement = $this->prepare(static::SAMPLE_STATEMENT);
        $statement->execute();
    }

    public function truncate(): void
    {
        $statement = $this->prepare("TRUNCATE TABLE `{$this->getTableName()}`");
        $statement->execute();
    }
    
    protected function getTableName()
    {
        $classNameParts = explode('\\', static::class);
        $className = array_pop($classNameParts);
        $tableName = preg_replace('#([a-z])([A-Z])#', '${1}_${2}', $className);
        return strtolower($tableName);
    }
}
