<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.ai.title')" :intro="__('components.ai.intro')"/>

    <x-layout.section>
        <x-layout.section-header :title="__('components.ai_llm.title')" :intro="__('components.ai.llm_teaser')"/>

        @if ($llmSummary['requests'] > 0)
            <x-layout.grid :cols="2" class="mt-4">
                <x-card.stat-card
                        :label="__('components.ai.stats.tokens_month')"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['completion_tokens'])"/>
                <x-card.stat-card
                        :label="__('components.ai.stats.requests_month')"
                        :value="\App\Helpers\Facades\HelperNumber::format($llmSummary['requests'], 0)"/>
            </x-layout.grid>
        @endif

        <div class="mt-4 flex flex-wrap gap-x-8 gap-y-2">
            <x-ui.link :href="localized_route('ai.llm.index')" class="inline-block text-base">
                {{ __('components.ai.to_models') }} <span aria-hidden="true">→</span>
            </x-ui.link>
            @if ($llmSummary['requests'] > 0)
                <x-ui.link :href="localized_route('ai.llm.analytics.index')" class="inline-block text-base">
                    {{ __('components.ai.to_analytics') }} <span aria-hidden="true">→</span>
                </x-ui.link>
            @endif
        </div>
    </x-layout.section>
</x-app-layout>
