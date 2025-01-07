<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Outstanding extends Report
{
    protected const string REPORT_SQL = "
        SELECT c.title, i.id, i. created_at, i.due_date, i.gross_amount, i.gross_amount-IFNULL(SUM(p.amount), 0) as outstanding_amount
        FROM invoice i
        LEFT JOIN payment p
        ON p.paid_at<i.due_date AND i.id=p.invoice_id
        LEFT JOIN client c
        ON c.id = i.client_id
        WHERE i.due_date < (CURRENT_DATE)
        GROUP BY i.id
        HAVING outstanding_amount>0
    ";

    protected const array MAP = [
        'title' => 'Nazwa przedsiębiorcy',
        'id' => 'Numer faktury',
        'created_at' => 'Data wystawienia',
        'due_date' => 'Termin płatności',
        'gross_amount' => 'Suma brutto',
        'outstanding_amount' => 'Kwota niedopłaty po terminie płatnośći'
    ];
}
