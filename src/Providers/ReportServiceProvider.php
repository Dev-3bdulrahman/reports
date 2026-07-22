<?php

namespace Dev3bdulrahman\Reports\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dev3bdulrahman\Reports\Models\Report;
use Dev3bdulrahman\Reports\Policies\ReportPolicy;
use Livewire\Livewire;

class ReportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'reports');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Translations', 'reports');

        // Register Policies
        Gate::policy(Report::class, ReportPolicy::class);

        // Register Livewire Components
        if (class_exists(Livewire::class)) {
            Livewire::component('reports-sales-report', \Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\SalesReport::class);
            Livewire::component('reports-inventory-report', \Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\InventoryReport::class);
            Livewire::component('reports-finance-report', \Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Reports\FinanceReport::class);
            Livewire::component('reports-builder', \Dev3bdulrahman\Reports\Http\Controllers\Web\Admin\Builder\Index::class);
        }
    }
}
