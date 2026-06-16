<div class="p-6 space-y-6 font-sans">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('reports::reports.inventory_report') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('reports::reports.summary') }}</p>
        </div>
        <button wire:click="generateReport()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            <span>{{ __('reports::reports.generate') }}</span>
        </button>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_valuation') }}</span>
            <p class="text-3xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ number_format($reportData['total_valuation'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-2">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.total_items') }}</span>
            <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($reportData['total_items'] ?? 0, 0) }}</p>
        </div>
    </div>

    {{-- Low Stock Section --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
            <h2 class="text-base font-black text-gray-900 dark:text-white">{{ __('reports::reports.low_stock_items') }}</h2>
            @if(!empty($reportData['low_stock_items']))
                <span class="px-2 py-0.5 text-xs font-bold bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-full">
                    {{ count($reportData['low_stock_items']) }}
                </span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.sku') }}</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.product_name') }}</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.quantity') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($reportData['low_stock_items'] ?? [] as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono text-gray-500 dark:text-gray-400">{{ $item['sku'] }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ $item['name'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold font-mono rounded-full bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400">
                                    {{ $item['quantity'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-green-400"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('reports::reports.no_low_stock') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
