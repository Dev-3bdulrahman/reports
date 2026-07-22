<div class="p-6 space-y-6 font-sans">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('reports::reports.finance_report') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('reports::reports.overview') }}</p>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.start_date') }}</label>
            <input type="date" wire:model="startDate" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.end_date') }}</label>
            <input type="date" wire:model="endDate" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
        <button wire:click="generateReport()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all">
            {{ __('reports::reports.generate') }}
        </button>
    </div>

    {{-- P&L Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Revenue --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 flex items-center justify-center bg-green-50 dark:bg-green-900/20 rounded-xl">
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-green-600 dark:text-green-400"></i>
                </span>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_revenue') }}</span>
            </div>
            <p class="text-3xl font-black text-green-600 dark:text-green-400 font-mono">
                {{ number_format($reportData['total_revenue'] ?? 0, 2) }}
            </p>
        </div>

        {{-- Expense --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-900/20 rounded-xl">
                    <i data-lucide="arrow-down-right" class="w-4 h-4 text-red-500 dark:text-red-400"></i>
                </span>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_expense') }}</span>
            </div>
            <p class="text-3xl font-black text-red-500 dark:text-red-400 font-mono">
                {{ number_format($reportData['total_expense'] ?? 0, 2) }}
            </p>
        </div>

        {{-- Net Profit --}}
        @php $netProfit = $reportData['net_profit'] ?? 0; @endphp
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border-2 {{ $netProfit >= 0 ? 'border-blue-200 dark:border-blue-800/40' : 'border-red-200 dark:border-red-800/40' }} shadow-sm space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 flex items-center justify-center {{ $netProfit >= 0 ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-red-50 dark:bg-red-900/20' }} rounded-xl">
                    <i data-lucide="{{ $netProfit >= 0 ? 'trending-up' : 'trending-down' }}" class="w-4 h-4 {{ $netProfit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-500 dark:text-red-400' }}"></i>
                </span>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.net_profit') }}</span>
            </div>
            <p class="text-3xl font-black {{ $netProfit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-500 dark:text-red-400' }} font-mono">
                {{ ($netProfit >= 0 ? '+' : '') . number_format($netProfit, 2) }}
            </p>
        </div>
    </div>

    {{-- Visual bar comparison --}}
    @php
        $revenue = (float)($reportData['total_revenue'] ?? 0);
        $expense = (float)($reportData['total_expense'] ?? 0);
        $max = max($revenue, $expense, 1);
        $revPct = ($revenue / $max) * 100;
        $expPct = ($expense / $max) * 100;
    @endphp
    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
        <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ __('reports::reports.overview') }}</h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">
                    <span>{{ __('reports::reports.total_revenue') }}</span>
                    <span class="font-mono">{{ number_format($revenue, 2) }}</span>
                </div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ $revPct }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">
                    <span>{{ __('reports::reports.total_expense') }}</span>
                    <span class="font-mono">{{ number_format($expense, 2) }}</span>
                </div>
                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full bg-red-400 rounded-full transition-all duration-500" style="width: {{ $expPct }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
