<?php

namespace Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports;

use Livewire\Component;
use Dev3bdulrahman\Reports\Services\ReportService;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class SalesReport extends Component
{
    public string $startDate = '';
    public string $endDate   = '';

    public array $reportData = [];

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate   = now()->endOfMonth()->toDateString();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $companyId = session('active_company_id', 1);
        $service = app(ReportService::class);
        $this->reportData = $service->getSalesReport($companyId, $this->startDate, $this->endDate);
    }

    public function exportCsv(): mixed
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=sales_report_" . now()->toDateString() . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                __('reports::reports.invoice_number'),
                __('reports::reports.date'),
                __('reports::reports.customer'),
                __('reports::reports.total'),
                __('reports::reports.paid'),
                __('reports::reports.status')
            ]);

            foreach ($this->reportData['invoices'] as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->invoice_date->toDateString(),
                    $invoice->customer?->name ?? '—',
                    $invoice->grand_total,
                    $invoice->paid_amount,
                    $invoice->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('reports::livewire.admin.reports.sales')
            ->title(__('reports::reports.sales_report'));
    }
}
