<?php

declare(strict_types=1);

namespace Task1\Model\Db\Report;

use Task1\Model\Db\Report;

class Excess extends Report
{
    protected array $having = [
        "balance>0"
    ];

    protected const string REPORT_SQL = "
         SELECT t1.title, IFNULL(SUM(p.amount), 0)-t1.invoiced as balance 
         FROM payment p
         LEFT JOIN invoice i
         ON p.invoice_id=i.id
         LEFT JOIN
         (
            SELECT c.title, c.id as payer_id, IFNULL(SUM(i.gross_amount), 0) as invoiced
            FROM invoice i
            INNER JOIN client c 
            ON c.id=i.client_id      
            GROUP BY c.id
        ) t1
        ON i.client_id=t1.payer_id
        GROUP BY i.client_id
        HAVING balance>0
    ";
    
 
    protected const array MAP = [
        'title' => 'Nazwa przedsiębiorcy',
        'balance' => 'Kwota nadpłaty' 
    ];
}
