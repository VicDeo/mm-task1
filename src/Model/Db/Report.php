<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;
use Task1\Model\Db\ReportInterface;

abstract class Report extends Db implements ReportInterface
{
    protected array $filters = [];
    protected array $sortBy = [];

    public function setFilters(array $filters): ReportInterface
    {
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
        $sortBy = array_keys($this->sortBy);
        if (count($sortBy) > 0) {
            $sortField = $sortBy[0];
            $reportSql .= " ORDER BY {$sortField} {$this->sortBy[$sortField]}";
        }

        $s = $this->prepare($reportSql);
        $s->execute();
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
