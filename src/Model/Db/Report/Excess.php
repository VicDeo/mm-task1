<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Excess extends Report
{
    protected const string REPORT_SQL = "
        SELECT c.*, SUM(p.amount)-SUM(i.gross_amount) AS balance FROM invoice i 
        LEFT JOIN client c ON i.client_id=c.id
        LEFT JOIN payment p ON p.bank_account=c.bank_account
        GROUP BY c.id
        HAVING balance > 0;
    ";
}
