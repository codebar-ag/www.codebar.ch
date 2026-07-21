<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai_llm_analytics.title')"/>
    <p class="text-gray-800">{{ __('components.ai_llm_analytics.intro') }}</p>

    <x-section>
        <form method="GET" action="{{ url()->current() }}" x-data="autoSubmit" x-on:change="submit"
              class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-llm-analytics.filter-combobox name="year"
                    :label="__('components.ai_llm_analytics.filter.year_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_years')"
                    :value="$year"
                    :options="$years"/>

            <x-llm-analytics.filter-combobox name="month"
                    :label="__('components.ai_llm_analytics.filter.month_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_months')"
                    :value="$month ? $monthOptions->get($month) : null"
                    :options="$monthOptions->values()"/>

            <x-llm-analytics.filter-combobox name="model"
                    :label="__('components.ai_llm_analytics.filter.model_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_models')"
                    :value="$modelLabel"
                    :options="$modelOptions"/>

            <noscript>
                <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-medium transition text-white bg-(--brand) hover:bg-brand-strong">
                    {{ __('components.ai_llm_analytics.filter.apply') }}
                </button>
            </noscript>
        </form>
    </x-section>

    @if ($totalSummary['requests'] > 0)
        <x-section>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-llm-analytics.stat-card
                        :label="\App\Helpers\Facades\HelperDate::monthLabel(now()->format('Y-m'))"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['completion_tokens'])"/>
                <x-llm-analytics.stat-card
                        :label="now()->year"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['completion_tokens'])"/>
                <x-llm-analytics.stat-card
                        :label="__('components.ai_llm_analytics.table.total')"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['completion_tokens'])"/>
            </div>
        </x-section>
    @endif

    @if ($periods->isEmpty())
        <x-section>
            <p class="text-gray-600">{{ __('components.ai_llm_analytics.empty') }}</p>
        </x-section>
    @else
        <x-section>
            <x-ai-llm.card>
                <x-h2 :title="__('components.ai_llm_analytics.table.title')"/>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2 pr-4 font-medium">{{ __('components.ai_llm_analytics.table.period') }}</th>
                            <th class="hidden sm:table-cell py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.prompt_tokens') }}</th>
                            <th class="hidden sm:table-cell py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.completion_tokens') }}</th>
                            <th class="py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.total_tokens') }}</th>
                            <th class="py-2 font-medium text-right">{{ __('components.ai_llm_analytics.table.requests') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($periods as $row)
                            <tr class="border-b border-gray-100 text-gray-800">
                                <td class="py-2 pr-4 whitespace-nowrap">{{ \App\Helpers\Facades\HelperDate::monthLabel($row['label']) }}</td>
                                <td class="hidden sm:table-cell py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($row['prompt_tokens'], 0) }}</td>
                                <td class="hidden sm:table-cell py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($row['completion_tokens'], 0) }}</td>
                                <td class="py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($row['total_tokens'], 0) }}</td>
                                <td class="py-2 text-right">{{ \App\Helpers\Facades\HelperNumber::format($row['requests'], 0) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="text-gray-800 font-semibold">
                            <td class="py-2 pr-4">{{ __('components.ai_llm_analytics.table.total') }}</td>
                            <td class="hidden sm:table-cell py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['prompt_tokens'], 0) }}</td>
                            <td class="hidden sm:table-cell py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['completion_tokens'], 0) }}</td>
                            <td class="py-2 pr-4 text-right">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['total_tokens'], 0) }}</td>
                            <td class="py-2 text-right">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['requests'], 0) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </x-ai-llm.card>
        </x-section>

        @if ($periods->hasPages())
            <x-section>
                {{ $periods->onEachSide(1)->links() }}
            </x-section>
        @endif
    @endif

    <x-section>
        <x-a :href="localized_route('ai.llm.index')" label="← {{ __('components.ai_llm_analytics.back') }}"
             class-attributes="inline-block text-base"/>
    </x-section>
</x-app-layout>
