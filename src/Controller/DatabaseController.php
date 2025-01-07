<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Http\Message\Response\Json;

class DatabaseController extends Controller
{
    #[Route(method: 'GET', path: '/db/create', name: 'db_create')]
    public function create(): Json
    {
        $responseData = [
            'data' => [],
            'status' => 'success'
        ];
        try {
            $this->getApp()->getDbTable('Client')->create();
            $this->getApp()->getDbTable('Invoice')->create();
            $this->getApp()->getDbTable('InvoiceItem')->create();
            $this->getApp()->getDbTable('Payment')->create();
        } catch (\PDOException $e) {
            $responseData = [
                'data' => [],
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return new Json($responseData);
    }

    #[Route(method: 'GET', path: '/db/seed', name: 'db_seed')]
    public function seed(): Json
    {
        $responseData = [
            'data' => [],
            'status' => 'success'
        ];
        try {
            $this->getApp()->getDbTable('Client')->truncate();
            $this->getApp()->getDbTable('Invoice')->truncate();
            $this->getApp()->getDbTable('InvoiceItem')->truncate();
            $this->getApp()->getDbTable('Payment')->truncate();
            $this->getApp()->getDbTable('Client')->seed();
            $this->getApp()->getDbTable('Invoice')->seed();
            $this->getApp()->getDbTable('InvoiceItem')->seed();
            $this->getApp()->getDbTable('Payment')->seed();
        } catch (\PDOException $e) {
            $responseData = [
                'data' => [],
                'status' => 'error',
                'message' => $e->getMessage()
            ];
       }
        return new Json($responseData);
    }
}