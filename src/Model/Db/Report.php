<?php

declare(strict_types=1);

namespace Task1\Model\Db;

use Task1\Model\Db;

abstract class Report extends Db implements ReportInterface
{
    public function getData(): array
    {
        $s = $this->prepare(static::REPORT_SQL);
        $s->execute();
        $rawResult = $s->fetchAll(\PDO::FETCH_ASSOC);

        return $this->mapResult($rawResult);
    }

    protected function mapResult(array $rawResult): array
    {
        $mapped = [];
        $sourceKeys = array_keys(static::MAP);
        foreach ($rawResult as $row){
            $mappedRow = [];
            foreach ($row as $oldKey => $value){
                if (in_array($oldKey, $sourceKeys)){
                    $newKey = static::MAP[$oldKey];
                    $mappedRow[$newKey] = $value;
                }
            }
            $mapped[] = $mappedRow;
        }
        return $mapped;
    }
}
