<?php

namespace App\Http\Controllers;

use App\Services\CashFlow\CashFlowReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class TrueCashFlowController extends Controller
{
    public function __construct(protected CashFlowReportService $cashFlowReportService)
    {
    }

    public function previewPdf()
    {
        return $this->renderPdf()->stream('cash_flow.pdf');
    }

    public function downloadPdf()
    {
        return $this->renderPdf()->download('cash_flow.pdf');
    }

    protected function renderPdf()
    {
        $data = $this->cashFlowReportService->build();

        return Pdf::loadView('reports.trueCashFlow', $data)
            ->setPaper('a4', 'portrait');
    }
}
