<?php

namespace Dev3bdulrahman\Reports\Services;

use Illuminate\Support\Facades\DB;
use Dev3bdulrahman\Sales\Models\Invoice as SalesInvoice;
use Dev3bdulrahman\Purchases\Models\SupplierInvoice;
use Dev3bdulrahman\Inventory\Models\StockItem;
use App\Models\Product;

class ReportService
{
    /**
     * Get Sales report summary & detailed invoice list
     */
    public function getSalesReport(int $companyId, string $startDate, string $endDate): array
    {
        $invoices = SalesInvoice::where('company_id', $companyId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->get();

        $totalRevenue = $invoices->sum('grand_total');
        $totalPaid    = $invoices->sum('paid_amount');
        $totalDue     = $totalRevenue - $totalPaid;
        $orderCount   = $invoices->count();
        $avgValue     = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        return [
            'total_revenue' => (float)$totalRevenue,
            'total_paid'    => (float)$totalPaid,
            'total_due'     => (float)$totalDue,
            'order_count'   => $orderCount,
            'avg_order_value' => (float)$avgValue,
            'invoices'      => $invoices,
        ];
    }

    /**
     * Get inventory valuation summary (Stock valuation & low stock items)
     */
    public function getInventoryValuation(int $companyId): array
    {
        $stockItems = StockItem::where('company_id', $companyId)
            ->with('product')
            ->get();

        $totalValuation = 0.0;
        $totalItems = 0;
        $lowStockItems = [];

        foreach ($stockItems as $item) {
            $product = $item->product;
            if ($product) {
                $cost = (float)$product->cost_price;
                $qty = (float)$item->quantity;
                $totalValuation += ($qty * $cost);
                $totalItems += $qty;

                if ($qty < 10) { // arbitrary threshold for low stock alert
                    $lowStockItems[] = [
                        'sku' => $product->sku,
                        'name' => $product->translated_name,
                        'quantity' => $qty,
                    ];
                }
            }
        }

        return [
            'total_valuation' => $totalValuation,
            'total_items'     => $totalItems,
            'low_stock_items' => $lowStockItems,
        ];
    }

    /**
     * Get Profit & Loss comparing Sales (Revenue) against Purchases (Expense)
     */
    public function getProfitAndLoss(int $companyId, string $startDate, string $endDate): array
    {
        $salesSum = SalesInvoice::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('grand_total');

        $purchasesSum = SupplierInvoice::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->sum('grand_total');

        $netProfit = $salesSum - $purchasesSum;

        return [
            'total_revenue' => (float)$salesSum,
            'total_expense' => (float)$purchasesSum,
            'net_profit'    => (float)$netProfit,
        ];
    }

    /**
     * Customer transaction statement
     */
    public function getCustomerStatement(int $companyId, int $customerId, string $startDate, string $endDate): array
    {
        $invoices = SalesInvoice::where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->orderBy('invoice_date')
            ->get();

        $totalInvoiced = $invoices->sum('grand_total');
        $totalPaid     = $invoices->sum('paid_amount');
        $balance       = $totalInvoiced - $totalPaid;

        return [
            'total_invoiced' => (float)$totalInvoiced,
            'total_paid'     => (float)$totalPaid,
            'balance'        => (float)$balance,
            'invoices'       => $invoices,
        ];
    }
}
