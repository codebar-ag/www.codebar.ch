<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.ai_llm_analytics.title')" :intro="__('components.ai_llm_analytics.intro')"/>

    <x-layout.section>
        <form method="GET" action="{{ url()->current() }}" x-data="autoSubmit" x-on:change="submit"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-form.combobox name="year"
                    :label="__('components.ai_llm_analytics.filter.year_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_years')"
                    :value="$year"
                    :options="$years"/>

            <x-form.combobox name="month"
                    :label="__('components.ai_llm_analytics.filter.month_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_months')"
                    :value="$month ? $monthOptions->get($month) : null"
                    :options="$monthOptions->values()"/>

            <x-form.combobox name="model"
                    :label="__('components.ai_llm_analytics.filter.model_label')"
                    :placeholder="__('components.ai_llm_analytics.filter.all_models')"
                    :value="$modelLabel"
                    :options="$modelOptions"/>

            <noscript>
                <x-ui.button type="submit" :label="__('components.ai_llm_analytics.filter.apply')"/>
            </noscript>
        </form>
    </x-layout.section>

    @if ($totalSummary['requests'] > 0)
        <x-layout.section>
            <x-layout.grid :cols="3">
                <x-card.stat-card
                        :label="\App\Helpers\Facades\HelperDate::monthLabel(now()->format('Y-m'))"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($monthSummary['completion_tokens'])"/>
                <x-card.stat-card
                        :label="now()->year"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($yearSummary['completion_tokens'])"/>
                <x-card.stat-card
                        :label="__('components.ai_llm_analytics.table.total')"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($totalSummary['completion_tokens'])"/>
            </x-layout.grid>
        </x-layout.section>
    @endif

    @if ($lastSyncedAt && $latestDate)
        <x-layout.section class="mt-4 text-sm text-muted">
            <p>
                {{ __('components.ai_llm_analytics.last_synced', [
                    'datetime' => \App\Helpers\Facades\HelperDate::formatDateTime($lastSyncedAt),
                    'date' => \App\Helpers\Facades\HelperDate::formatDate($latestDate),
                ]) }}
            </p>
        </x-layout.section>
    @endif

    @if ($periods->isEmpty())
        <x-layout.section>
            <p class="text-muted">{{ __('components.ai_llm_analytics.empty') }}</p>
        </x-layout.section>
    @else
        <x-layout.section>
            <x-ui.panel class="px-6 pt-6 pb-4">
                <x-h2 :title="__('components.ai_llm_analytics.table.title')"/>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <caption class="sr-only">{{ __('components.ai_llm_analytics.table.title') }}</caption>
                        <thead>
                        <tr class="text-left text-gray-500 border-b border-border">
                            <th scope="col" class="py-2 pr-4 font-medium">{{ __('components.ai_llm_analytics.table.period') }}</th>
                            <th scope="col" class="hidden sm:table-cell py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.prompt_tokens') }}</th>
                            <th scope="col" class="hidden sm:table-cell py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.completion_tokens') }}</th>
                            <th scope="col" class="py-2 pr-4 font-medium text-right">{{ __('components.ai_llm_analytics.table.total_tokens') }}</th>
                            <th scope="col" class="py-2 font-medium text-right">{{ __('components.ai_llm_analytics.table.requests') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($periods as $row)
                            <tr class="border-b border-border-soft text-gray-800">
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
            </x-ui.panel>
        </x-layout.section>

        @if ($periods->hasPages())
            <x-layout.section>
                {{ $periods->onEachSide(1)->links() }}
            </x-layout.section>
        @endif
    @endif

    <x-layout.section>
        <x-ui.link :href="localized_route('ai.llm.index')" class="inline-block text-base">
            <span aria-hidden="true">←</span> {{ __('components.ai_llm_analytics.back') }}
        </x-ui.link>
    </x-layout.section>
</x-app-layout>
