<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Response\Template;

class DatabaseController extends Controller
{
    #[Route(method: 'GET', path: '/db/create', name: 'db_create')]
    public function create(): Template
    {
        try {
            $this->getApp()->getDbTable('Client')->init();
            $this->getApp()->getDbTable('Invoice')->init();
            $this->getApp()->getDbTable('InvoiceItem')->init();
            $this->getApp()->getDbTable('Payment')->init();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return new Template('page.php');
    }

    #[Route(method: 'GET', path: '/db/seed', name: 'db_seed')]
    public function seed(): Template
    {
        try {
            $this->getApp()->getDbTable('Client')->truncate();
            $this->getApp()->getDbTable('Invoice')->truncate();
            $this->getApp()->getDbTable('InvoiceItem')->truncate();
            $this->getApp()->getDbTable('Payment')->truncate();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return new Template('page.php');
    }
}