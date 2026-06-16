<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\SalesReport;
use Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\InventoryReport;
use Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\FinanceReport;

Route::middleware(['web', 'auth'])->prefix('admin/reports')->group(function () {
    Route::get('sales',     SalesReport::class)->name('admin.reports.sales');
    Route::get('inventory', InventoryReport::class)->name('admin.reports.inventory');
    Route::get('finance',   FinanceReport::class)->name('admin.reports.finance');
});
