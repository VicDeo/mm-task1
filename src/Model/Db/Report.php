<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;
use Task1\Model\Db\ReportInterface;

abstract class Report extends Db implements ReportInterface
{
    protected array $filters = [];

    protected array $queryValues = [];

    protected array $sortBy = [];

    protected array $having = [];

    public function setFilters(array $filters): ReportInterface
    {
        $reverseMap = array_flip(static::MAP);
        foreach ($filters as $translatedField => $value) {
            $filterByField = $reverseMap[$translatedField];
            $this->filters[$filterByField] = $value;
        }
        return $this;
    }

    public function setSortOrder(string $translatedField, string $direction): ReportInterface
    {
        $reverseMap = array_flip(static::MAP);
        $sortByField = $reverseMap[$translatedField];
        $this->sortBy[$sortByField] = $direction;
        return $this;
    }

    public function getData(): array
    {
        $reportSql = static::REPORT_SQL;

        foreach ($this->filters as $k => $v) {
            $this->having[] = "{$k} LIKE :{$k}";
            $this->queryValues[":{$k}"] = "%{$v}%";
        }

        if (count($this->having) > 0) {
            $h = implode(' AND ', $this->having);
            $reportSql .= "HAVING $h";
        }

        $sortBy = array_keys($this->sortBy);
        if (count($sortBy) > 0) {
            $sortField = $sortBy[0];
            $reportSql .= " ORDER BY {$sortField} {$this->sortBy[$sortField]}";
        }

        $s = $this->prepare($reportSql);
        $s->execute($this->queryValues);
        $rawResult = $s->fetchAll(\PDO::FETCH_ASSOC);

        return $this->mapResult($rawResult);
    }

    protected function mapResult(array $rawResult): array
    {
        $mapped = [];
        $sourceKeys = array_keys(static::MAP);
        foreach ($rawResult as $row) {
            $mappedRow = [];
            foreach ($row as $oldKey => $value) {
                if (in_array($oldKey, $sourceKeys)) {
                    $newKey = static::MAP[$oldKey];
                    $mappedRow[$newKey] = $value;
                }
            }
            $mapped[] = $mappedRow;
        }
        return $mapped;
    }
}
