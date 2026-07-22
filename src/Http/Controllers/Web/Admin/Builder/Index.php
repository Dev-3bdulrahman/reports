<?php

namespace Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Builder;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Reports\Services\ReportBuilderService;
use Dev3bdulrahman\Reports\Models\Report;

class Index extends Component
{
    #[Url(as: 'module')]
    public string $selectedModule = '';

    #[Url(as: 'table')]
    public string $selectedTable = '';

    public array $availableModules = [];
    public array $availableTables = [];
    public array $availableFields = [];
    public array $selectedFields = [];
    public array $filters = [];
    public array $previewResults = [];

    public string $reportName = '';
    public string $reportDescription = '';
    public ?int $editReportId = null;

    public bool $showPreview = false;

    protected $listeners = ['removeField', 'removeFilter'];

    public function mount(ReportBuilderService $service): void
    {
        $this->availableModules = $service->getAvailableModules();
    }

    public function updatedSelectedModule(): void
    {
        $this->selectedTable = '';
        $this->availableTables = [];
        $this->availableFields = [];
        $this->selectedFields = [];
        $this->filters = [];
        $this->previewResults = [];
        $this->showPreview = false;

        if ($this->selectedModule && isset($this->availableModules[$this->selectedModule])) {
            $this->availableTables = $this->availableModules[$this->selectedModule];
        }
    }

    public function updatedSelectedTable(ReportBuilderService $service): void
    {
        $this->availableFields = [];
        $this->selectedFields = [];
        $this->filters = [];
        $this->previewResults = [];
        $this->showPreview = false;

        if ($this->selectedModule && $this->selectedTable) {
            $this->availableFields = $service->getAvailableFields($this->selectedModule, $this->selectedTable);
        }
    }

    public function addField(string $field): void
    {
        if (!in_array($field, $this->selectedFields)) {
            $this->selectedFields[] = $field;
        }
    }

    public function removeField(string $field): void
    {
        $this->selectedFields = array_values(array_filter($this->selectedFields, fn($f) => $f !== $field));
    }

    public function addFilter(): void
    {
        $this->filters[] = ['field' => '', 'operator' => 'equals', 'value' => ''];
    }

    public function removeFilter(int $index): void
    {
        unset($this->filters[$index]);
        $this->filters = array_values($this->filters);
    }

    public function preview(ReportBuilderService $service): void
    {
        if (!$this->selectedModule || !$this->selectedTable) return;

        $report = new Report();
        $report->module = $this->selectedModule;
        $report->type = $this->selectedTable;
        $report->filters = ['filters' => $this->filters];

        $this->previewResults = $service->buildQuery($report);
        $this->showPreview = true;
    }

    public function save(ReportBuilderService $service): void
    {
        $this->validate([
            'reportName' => 'required|string|max:255',
            'selectedModule' => 'required',
            'selectedTable' => 'required',
        ]);

        $data = [
            'company_id' => session('active_company_id', 1),
            'name' => $this->reportName,
            'module' => $this->selectedModule,
            'type' => $this->selectedTable,
            'filters' => ['filters' => $this->filters, 'fields' => $this->selectedFields],
            'is_active' => true,
            'created_by' => auth()->id(),
        ];

        if ($this->editReportId) {
            $service->updateReport($this->editReportId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('reports::reports.report_updated')]);
        } else {
            $service->saveReport($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('reports::reports.report_created')]);
        }

        $this->reportName = '';
        $this->reportDescription = '';
        $this->editReportId = null;
    }

    public function editReport(int $id): void
    {
        $report = Report::findOrFail($id);
        $this->editReportId = $report->id;
        $this->reportName = $report->name;
        $this->reportDescription = $report->description ?? '';
        $this->selectedModule = $report->module;
        $this->selectedTable = $report->type;
        $this->selectedFields = $report->filters['fields'] ?? [];
        $this->filters = $report->filters['filters'] ?? [];
        $this->updatedSelectedModule();
    }

    #[Layout('layouts.admin')]
    public function render(ReportBuilderService $service)
    {
        $savedReports = Report::where('company_id', session('active_company_id', 1))->latest()->get();

        return view('reports::livewire.admin.builder.index', [
            'savedReports' => $savedReports,
        ])->title(__('reports::reports.report_builder'));
    }
}
