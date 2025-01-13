<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Underpayment extends Report
{
    protected const string REPORT_SQL = "
        SELECT c.title, i.id, i.created_at, i.gross_amount, i.gross_amount-t1.total_paid as underpayment_amount
        FROM invoice i 
        LEFT JOIN client c ON i.client_id=c.id
        LEFT JOIN (
            SELECT invoice_id, SUM(amount) as total_paid
            FROM payment p
            GROUP BY invoice_id
        ) t1
        ON i.id=t1.invoice_id 
        HAVING underpayment_amount>0
    ";

    protected const array MAP = [
        'title' => 'Nazwa przedsiębiorcy',
        'id' => 'Numer faktury',
        'created_at' => 'Data wystawienia',
        'due_date' => 'Termin płatności',
        'gross_amount' => 'Suma brutto',
        'underpayment_amount' => 'Kwota niedopłaty'
    ];
}
