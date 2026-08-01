<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.ai_llm_analytics.title')" :intro="__('components.ai_llm_analytics.intro')" :breadcrumbs="[
        ['label' => __('AI'), 'url' => localized_route('ai.index')],
        ['label' => __('components.ai_llm.title'), 'url' => localized_route('ai.llm.index')],
        ['label' => __('components.ai_llm_analytics.title')],
    ]"/>

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

    @if ($lastSyncedAt)
        <div class="mt-4 text-xs text-muted">
            <p>
                {{ __('components.ai_llm_analytics.last_synced', [
                    'datetime' => \App\Helpers\Facades\HelperDate::formatDateTime($lastSyncedAt),
                ]) }}
            </p>
        </div>
    @endif

    @if ($periods->isEmpty())
        <x-layout.section>
            <p class="text-muted">{{ __('components.ai_llm_analytics.empty') }}</p>
        </x-layout.section>
    @else
        <x-layout.section>
            <x-ui.panel class="px-6 pt-6 pb-4">
                <x-h2 :title="__('components.ai_llm_analytics.table.title')"/>
                <x-ui.table :caption="__('components.ai_llm_analytics.table.title')">
                    <thead>
                    <x-ui.table.row variant="head">
                        <x-ui.table.cell as="th" scope="col">{{ __('components.ai_llm_analytics.table.period') }}</x-ui.table.cell>
                        <x-ui.table.cell as="th" scope="col" align="end" :hide="true">{{ __('components.ai_llm_analytics.table.prompt_tokens') }}</x-ui.table.cell>
                        <x-ui.table.cell as="th" scope="col" align="end" :hide="true">{{ __('components.ai_llm_analytics.table.completion_tokens') }}</x-ui.table.cell>
                        <x-ui.table.cell as="th" scope="col" align="end">{{ __('components.ai_llm_analytics.table.total_tokens') }}</x-ui.table.cell>
                        <x-ui.table.cell as="th" scope="col" align="end">{{ __('components.ai_llm_analytics.table.requests') }}</x-ui.table.cell>
                    </x-ui.table.row>
                    </thead>
                    <tbody>
                    @foreach ($periods as $row)
                        <x-ui.table.row>
                            <x-ui.table.cell class="whitespace-nowrap">{{ \App\Helpers\Facades\HelperDate::monthLabel($row['label']) }}</x-ui.table.cell>
                            <x-ui.table.cell align="end" :hide="true">{{ \App\Helpers\Facades\HelperNumber::format($row['prompt_tokens'], 0) }}</x-ui.table.cell>
                            <x-ui.table.cell align="end" :hide="true">{{ \App\Helpers\Facades\HelperNumber::format($row['completion_tokens'], 0) }}</x-ui.table.cell>
                            <x-ui.table.cell align="end">{{ \App\Helpers\Facades\HelperNumber::format($row['total_tokens'], 0) }}</x-ui.table.cell>
                            <x-ui.table.cell align="end">{{ \App\Helpers\Facades\HelperNumber::format($row['requests'], 0) }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <x-ui.table.row variant="foot">
                        <x-ui.table.cell>{{ __('components.ai_llm_analytics.table.total') }}</x-ui.table.cell>
                        <x-ui.table.cell align="end" :hide="true">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['prompt_tokens'], 0) }}</x-ui.table.cell>
                        <x-ui.table.cell align="end" :hide="true">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['completion_tokens'], 0) }}</x-ui.table.cell>
                        <x-ui.table.cell align="end">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['total_tokens'], 0) }}</x-ui.table.cell>
                        <x-ui.table.cell align="end">{{ \App\Helpers\Facades\HelperNumber::format($grandTotal['requests'], 0) }}</x-ui.table.cell>
                    </x-ui.table.row>
                    </tfoot>
                </x-ui.table>
            </x-ui.panel>
        </x-layout.section>

        @if ($periods->hasPages())
            <x-layout.section>
                <x-ui.pagination :paginator="$periods->onEachSide(1)"/>
            </x-layout.section>
        @endif
    @endif

    <x-layout.section>
        <x-ui.arrow-link :href="localized_route('ai.llm.index')" direction="back"
                         :label="__('components.ai_llm_analytics.back')"/>
    </x-layout.section>
</x-app-layout>
