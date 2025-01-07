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
        return $s->fetchAll();
    }
}
