<div class="p-6 space-y-6 font-sans">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('reports::reports.sales_report') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('reports::reports.summary') }}</p>
        </div>
        <button wire:click="exportCsv()" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>{{ __('reports::reports.export') }}</span>
        </button>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.start_date') }}</label>
            <input type="date" wire:model="startDate" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.end_date') }}</label>
            <input type="date" wire:model="endDate" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
        <button wire:click="generateReport()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all">
            {{ __('reports::reports.generate') }}
        </button>
    </div>

    {{-- KPIs Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_revenue') }}</span>
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ number_format($reportData['total_revenue'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_paid') }}</span>
            <p class="text-2xl font-black text-green-600 dark:text-green-400 font-mono">{{ number_format($reportData['total_paid'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_due') }}</span>
            <p class="text-2xl font-black text-red-500 dark:text-red-400 font-mono">{{ number_format($reportData['total_due'] ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Secondary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">{{ __('reports::reports.order_count') }}</span>
                <span class="text-3xl font-black text-gray-950 dark:text-gray-100 font-mono">{{ $reportData['order_count'] ?? 0 }}</span>
            </div>
            <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-200 dark:text-gray-800"></i>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">{{ __('reports::reports.avg_order_value') }}</span>
                <span class="text-3xl font-black text-gray-950 dark:text-gray-100 font-mono">{{ number_format($reportData['avg_order_value'] ?? 0, 2) }}</span>
            </div>
            <i data-lucide="trending-up" class="w-10 h-10 text-gray-200 dark:text-gray-800"></i>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-gray-550 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.invoice_number') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.date') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.customer') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.paid') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($reportData['invoices'] ?? [] as $inv)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ $inv->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $inv->invoice_date->toDateString() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-950 dark:text-gray-200">{{ $inv->customer?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ number_format($inv->grand_total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ number_format($inv->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $inv->status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400' }}">
                                    {{ $inv->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No data found for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
