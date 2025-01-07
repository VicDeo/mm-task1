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
        $data = $this->getApp()->getReport('Excess')->getData();
        return new Json($data);
    }

    #[Route(method: 'GET', path: '/report/underpayment', name: 'report_underpayment')]
    public function underpayment(): Json
    {
        $data = $this->getApp()->getReport('Underpayment')->getData();
        return new Json($data);
    }

    #[Route(method: 'GET', path: '/report/outstanding', name: 'report_outstanding')]
    public function outstanding(): Json
    {
        $data = $this->getApp()->getReport('Outstanding')->getData();
        return new Json($data);
    }
}
