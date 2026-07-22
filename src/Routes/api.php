<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Reports\Http\Controllers\Api\ReportApiController;

Route::prefix('api/v1/reports')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {
    Route::get('/', [ReportApiController::class, 'index'])->name('api.v1.reports.index');
    Route::post('/', [ReportApiController::class, 'store'])->name('api.v1.reports.store');
    Route::get('/{report}', [ReportApiController::class, 'show'])->name('api.v1.reports.show');
    Route::delete('/{report}', [ReportApiController::class, 'destroy'])->name('api.v1.reports.destroy');
    Route::post('/{report}/generate', [ReportApiController::class, 'generate'])->name('api.v1.reports.generate');
});
