<?php
declare(strict_types=1);

namespace Task1\Controller;

use Task1\Model\Attribute\Route;
use Task1\Model\Db\ReportInterface;
use Task1\Model\Http\Message\Response\Json;

class ReportController extends Controller
{
    #[Route(method: 'GET', path: '/report/excess', name: 'report_excess')]
    public function excess(): Json
    {
        return $this->processReport('Excess');
    }

    #[Route(method: 'GET', path: '/report/underpayment', name: 'report_underpayment')]
    public function underpayment(): Json
    {
        return $this->processReport('Underpayment');
    }

    #[Route(method: 'GET', path: '/report/outstanding', name: 'report_outstanding')]
    public function outstanding(): Json
    {
        return $this->processReport('Outstanding');
    }

    private function processReport(string $reportName): Json
    {
        try {
            $report = $this->getApp()->getReport($reportName);
            $filters = [];
            $fc = 1;
            while (($filterName = $this->getUrlParam("filter_{$fc}", false)) !== false) {
                $filterValue = $this->getUrlParam("filter_value_{$fc}", '');
                $filters[$filterName] = $filterValue;
                $fc++;
            }

            if (count($filters) > 0) {
                $report->setFilters($filters);
            }

            $sortBy = $this->getUrlParam('sort_by', '');
            if ($sortBy !== '') {
                $sortDir = $this->getUrlParam('sort_dir', ReportInterface::SORT_ORDER_DESC);
                $report->setSortOrder($sortBy, $sortDir);
            }

            $responseData = [
                'data' => $report->getData(),
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            $responseData = [
                'data' => [],
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return new Json($responseData);
    }
}
