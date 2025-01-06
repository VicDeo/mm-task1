<?php

declare(strict_types=1);

namespace Task1\Model;

use PDO;

abstract class Db
{
    private PDO $connection;

    public function __construct(
        private string $host,
        private string $database,
        private string $user,
        private string $password
    ) {
    }

    public function prepare(string $query, ?array $params=[]): \PDOStatement|false
    {
        $statement = $this->getConnection()->prepare($query, $params);
        return $statement;
    }

    protected function getConnection(): PDO
    {
       if (!isset($this->connection)){
            $this->connection = new PDO(
                $this->getDsn(),
                $this->user,
                $this->password
            );
       }
       return $this->connection;
    }

    protected function getDsn(): string
    {
        return "mysql:dbname={$this->database};host={$this->host};";
    }
}
