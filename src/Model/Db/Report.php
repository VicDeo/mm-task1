<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;
use Task1\Model\Db\ReportInterface;

abstract class Report extends Db implements ReportInterface
{
    protected array $filters = [];

    protected array $allowedFilterTypes = [
        'contains', 'equals', 'less', 'greater'
    ];

    protected array $queryValues = [];

    protected array $sortBy = [];

    protected array $having = [];

    public function setFilters(array $filters): ReportInterface
    {
        $reverseMap = array_flip(static::MAP);
        foreach ($filters as $translatedField => $details) {
            $filterType = $details['type'];
            if (in_array($filterType, $this->allowedFilterTypes) === false){
                continue;
            }
            $filterByField = $reverseMap[$translatedField];
            $this->filters[$filterByField] = [
                'value' => $details['value'],
                'type' => $filterType
            ];
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

        foreach ($this->filters as $field => $details) {
            $value = $details['value'];
            // I know, this is ugly ;)
            if ($details['type'] === 'contains') {
                $this->having[] = "{$field} LIKE :{$field}";
                $this->queryValues[":{$field}"] = "%{$value}%";
            } elseif ($details['type'] === 'equals') {
                $this->having[] = "{$field} = :{$field}";
                $this->queryValues[":{$field}"] = "{$value}";
            } elseif ($details['type'] === 'less') {
                $this->having[] = "{$field} < :{$field}";
                $this->queryValues[":{$field}"] = "{$value}";
            } elseif ($details['type'] === 'greater') {
                $this->having[] = "{$field} > :{$field}";
                $this->queryValues[":{$field}"] = "{$value}";
            }
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

        return [
            'head' => array_values(static::MAP),
            'body' => $this->mapResult($rawResult)
        ];
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
