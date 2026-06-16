<?php

namespace Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports;

use Livewire\Component;
use Dev3bdulrahman\Reports\Services\ReportService;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class FinanceReport extends Component
{
    public string $startDate = '';
    public string $endDate   = '';

    public array $reportData = [];

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->toDateString();
        $this->endDate   = now()->endOfYear()->toDateString();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $companyId = session('active_company_id', 1);
        $service = app(ReportService::class);
        $this->reportData = $service->getProfitAndLoss($companyId, $this->startDate, $this->endDate);
    }

    public function render()
    {
        return view('reports::livewire.admin.reports.finance')
            ->title(__('reports::reports.finance_report'));
    }
}
