<?php

namespace Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports;

use Livewire\Component;
use Dev3bdulrahman\Reports\Services\ReportService;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class InventoryReport extends Component
{
    public array $reportData = [];

    public function mount(): void
    {
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $companyId = session('active_company_id', 1);
        $service = app(ReportService::class);
        $this->reportData = $service->getInventoryValuation($companyId);
    }

    public function render()
    {
        return view('reports::livewire.admin.reports.inventory')
            ->title(__('reports::reports.inventory_report'));
    }
}
