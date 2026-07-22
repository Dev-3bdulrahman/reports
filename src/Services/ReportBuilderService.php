<?php

namespace Dev3bdulrahman\Reports\Services;

use Dev3bdulrahman\Reports\Models\Report;
use Dev3bdulrahman\Reports\Models\ReportFilter;
use Illuminate\Support\Facades\DB;

class ReportBuilderService
{
    protected array $availableModules = [
        'sales' => ['invoices', 'quotations', 'orders', 'payments', 'returns'],
        'purchases' => ['invoices', 'orders', 'payments', 'returns'],
        'inventory' => ['stock_items', 'stock_moves', 'warehouses', 'batches'],
        'accounting' => ['journal_entries', 'accounts', 'expenses'],
        'crm' => ['leads', 'customers', 'opportunities'],
        'hr' => ['employees', 'attendance', 'payroll', 'leaves'],
    ];

    protected array $availableFields = [
        'sales.invoices' => [
            'invoice_number', 'invoice_date', 'due_date', 'status',
            'subtotal', 'tax_total', 'discount_total', 'grand_total', 'paid_amount',
            'customer.name', 'created_at',
        ],
        'inventory.stock_items' => [
            'product.name', 'warehouse.name', 'quantity', 'batch.batch_number',
            'batch.expiry_date', 'average_cost',
        ],
        'accounting.journal_entries' => [
            'entry_number', 'entry_date', 'description', 'debit_total', 'credit_total',
            'account.name', 'created_at',
        ],
    ];

    public function getAvailableModules(): array
    {
        return $this->availableModules;
    }

    public function getAvailableFields(string $module, string $table): array
    {
        return $this->availableFields["{$module}.{$table}"] ?? [];
    }

    public function buildQuery(Report $report): array
    {
        $filterData = $report->filters ?? [];
        $module = $report->module;
        $type = $report->type;

        $query = $this->getBaseQuery($module, $type);

        if (!empty($filterData['filters'])) {
            foreach ($filterData['filters'] as $filter) {
                $this->applyFilter($query, $filter);
            }
        }

        return $query->get()->toArray();
    }

    protected function getBaseQuery(string $module, string $type)
    {
        return match ($module) {
            'sales' => DB::table("sales_{$type}"),
            'purchases' => DB::table("purchases_{$type}"),
            'inventory' => DB::table("inventory_{$type}"),
            'accounting' => DB::table("accounting_{$type}"),
            'crm' => DB::table("crm_{$type}s"),
            'hr' => DB::table("hr_{$type}"),
            default => throw new \Exception("Unknown module: {$module}"),
        };
    }

    protected function applyFilter($query, array $filter): void
    {
        $field = $filter['field'] ?? '';
        $operator = $filter['operator'] ?? '=';
        $value = $filter['value'] ?? '';

        if (empty($field)) return;

        switch ($operator) {
            case 'equals':
                $query->where($field, $value);
                break;
            case 'not_equals':
                $query->where($field, '!=', $value);
                break;
            case 'contains':
                $query->where($field, 'like', "%{$value}%");
                break;
            case 'greater_than':
                $query->where($field, '>', $value);
                break;
            case 'less_than':
                $query->where($field, '<', $value);
                break;
            case 'between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($field, $value);
                }
                break;
            case 'in':
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                }
                break;
        }
    }

    public function saveReport(array $data): Report
    {
        return Report::create($data);
    }

    public function updateReport(int $id, array $data): Report
    {
        $report = Report::findOrFail($id);
        $report->update($data);
        return $report;
    }

    public function deleteReport(int $id): bool
    {
        return Report::findOrFail($id)->delete();
    }
}
