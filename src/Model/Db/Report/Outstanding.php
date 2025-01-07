<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Outstanding extends Report
{
    protected const string REPORT_SQL = "
        SELECT i.*, p.id payment_id FROM invoice i 
        LEFT JOIN client c ON i.client_id=c.id
        LEFT JOIN payment p ON p.bank_account=c.bank_account 
        AND p.paid_at>=i.created_at AND p.paid_at<=i.due_date
        HAVING payment_id IS NULL;
    ";
}
