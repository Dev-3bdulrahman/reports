<div class="p-6 space-y-6 font-sans">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('reports::reports.report_builder') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('reports::reports.report_builder_subtitle') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.select_source') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.module') }}</label>
                        <select wire:model.live="selectedModule" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="">{{ __('reports::reports.select_module') }}</option>
                            @foreach($availableModules as $moduleKey => $tables)
                                <option value="{{ $moduleKey }}">{{ ucfirst($moduleKey) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.table') }}</label>
                        <select wire:model.live="selectedTable" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="">{{ __('reports::reports.select_table') }}</option>
                            @foreach($availableTables as $table)
                                <option value="{{ $table }}">{{ ucfirst(str_replace('_', ' ', $table)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if($availableFields)
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.available_fields') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableFields as $field)
                            <button wire:click="addField('{{ $field }}')" class="px-3 py-1.5 text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-400 transition-all @if(in_array($field, $selectedFields)) bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 @endif">
                                {{ str_replace('_', ' ', $field) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($selectedFields)
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.selected_fields') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($selectedFields as $field)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg">
                                {{ str_replace('_', ' ', $field) }}
                                <button wire:click="removeField('{{ $field }}')" class="text-blue-400 hover:text-red-500 transition-colors">&times;</button>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.filters') }}</h3>
                    <button wire:click="addFilter" class="px-3 py-1.5 text-xs font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-all">{{ __('reports::reports.add_filter') }}</button>
                </div>
                @foreach($filters as $index => $filter)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('reports::reports.field') }}</label>
                            <input type="text" wire:model="filters.{{ $index }}.field" placeholder="field_name" class="w-full px-3 py-2 text-xs bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('reports::reports.operator') }}</label>
                            <select wire:model="filters.{{ $index }}.operator" class="w-full px-3 py-2 text-xs bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="equals">{{ __('reports::reports.equals') }}</option>
                                <option value="not_equals">{{ __('reports::reports.not_equals') }}</option>
                                <option value="contains">{{ __('reports::reports.contains') }}</option>
                                <option value="greater_than">{{ __('reports::reports.greater_than') }}</option>
                                <option value="less_than">{{ __('reports::reports.less_than') }}</option>
                                <option value="between">{{ __('reports::reports.between') }}</option>
                                <option value="in">{{ __('reports::reports.in') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('reports::reports.value') }}</label>
                            <input type="text" wire:model="filters.{{ $index }}.value" placeholder="{{ __('reports::reports.filter_value') }}" class="w-full px-3 py-2 text-xs bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                        <button wire:click="removeFilter({{ $index }})" class="px-3 py-2 text-xs font-bold bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 rounded-lg hover:bg-red-100 transition-all">{{ __('reports::reports.remove') }}</button>
                    </div>
                @endforeach
            </div>

            <div class="flex gap-3">
                <button wire:click="preview" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                    {{ __('reports::reports.preview') }}
                </button>
            </div>

            @if($showPreview)
                <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr class="bg-gray-550 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                                    @foreach($selectedFields as $field)
                                        <th class="px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ str_replace('_', ' ', $field) }}</th>
                                    @endforeach
                                    @if(empty($selectedFields))
                                        <th class="px-4 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('reports::reports.data') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($previewResults as $row)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        @foreach($selectedFields as $field)
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ data_get($row, $field, '—') }}</td>
                                        @endforeach
                                        @if(empty($selectedFields))
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ json_encode($row) }}</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ max(count($selectedFields), 1) }}" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('reports::reports.no_data') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.save_report') }}</h3>
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.report_name') }}</label>
                    <input type="text" wire:model="reportName" placeholder="{{ __('reports::reports.report_name_placeholder') }}" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">{{ __('reports::reports.description') }}</label>
                    <textarea wire:model="reportDescription" rows="3" placeholder="{{ __('reports::reports.description_placeholder') }}" class="w-full px-4 py-2.5 text-sm bg-gray-550 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
                </div>
                <button wire:click="save" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                    {{ __('reports::reports.save') }}
                </button>
            </div>

            @if($savedReports->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('reports::reports.saved_reports') }}</h3>
                    <div class="space-y-2">
                        @foreach($savedReports as $saved)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $saved->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $saved->module }} / {{ $saved->type }}</p>
                                </div>
                                <button wire:click="editReport({{ $saved->id }})" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">{{ __('reports::reports.edit') }}</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
