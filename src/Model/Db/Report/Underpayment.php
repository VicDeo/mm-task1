<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Underpayment extends Report
{
    protected const string REPORT_SQL = "
        SELECT c.*, i.*, p.id FROM invoice i 
        LEFT JOIN client c ON i.client_id=c.id
        LEFT JOIN payment p ON p.bank_account=c.bank_account 
        AND p.paid_at>=i.created_at AND p.paid_at<=i.due_date
        WHERE p.id IS NOT NULL AND p.amount<i.gross_amount;
    ";
}
