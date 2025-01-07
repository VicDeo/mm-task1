<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Response\Json;

class ReportController extends Controller
{
    #[Route(method: 'GET', path: '/report/excess', name: 'report_excess')]
    public function excess(): Json
    {
$q = "
SELECT c.*, SUM(p.amount)-SUM(i.gross_amount) AS balance FROM invoice i 
LEFT JOIN client c ON i.client_id=c.id
LEFT JOIN payment p ON p.bank_account=c.bank_account
GROUP BY c.id
HAVING balance > 0
";
        $s = $this->getApp()->getDbTable('Client')->prepare($q);
        $s->execute();
        $r = $s->fetchAll();
    
        return new Json($r);
    }

    #[Route(method: 'GET', path: '/report/underpayment', name: 'report_underpayment')]
    public function underpayment(): Json
    {
        $q = "
SELECT c.*, i.*, p.id FROM invoice i 
LEFT JOIN client c ON i.client_id=c.id
LEFT JOIN payment p ON p.bank_account=c.bank_account 
  AND p.paid_at>=i.created_at AND p.paid_at<=i.due_date
WHERE p.id IS NOT NULL AND p.amount<i.gross_amount 
        ";
        $s = $this->getApp()->getDbTable('Client')->prepare($q);
        $s->execute();
        $r = $s->fetchAll();

        return new Json($r);
    }

    #[Route(method: 'GET', path: '/report/outstanding', name: 'report_outstanding')]
    public function outstanding(): Json
    {
        $q = "        
SELECT i.*, p.id FROM invoice i 
LEFT JOIN client c ON i.client_id=c.id
LEFT JOIN payment p ON p.bank_account=c.bank_account 
  AND p.paid_at>=i.created_at AND p.paid_at<=i.due_date
WHERE p.id IS NULL
        ";
        $s = $this->getApp()->getDbTable('Client')->prepare($q);
        $s->execute();
        $r = $s->fetchAll();

        return new Json($r);
    }
}
