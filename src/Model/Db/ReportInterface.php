<?php

declare(strict_types=1);

namespace Task1\Model\Db;

interface ReportInterface
{
    public const string SORT_ORDER_ASC = 'ASC';
    public const string SORT_ORDER_DESC = 'DESC';

    public function getData(): array;

    public function setFilters(array $filters): ReportInterface;

    public function setSortOrder(string $translatedField, string $direction): ReportInterface;
}
