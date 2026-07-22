<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai.title')"/>
    <p class="text-gray-800">{{ __('components.ai.intro') }}</p>

    <x-section>
        <x-h2 :title="__('components.ai_llm.title')"/>
        <p class="text-gray-600">{{ __('components.ai.llm_teaser') }}</p>

        @if ($llmSummary['requests'] > 0)
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-llm-analytics.stat-card
                        :label="__('components.ai.stats.tokens_month')"
                        :value="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['total_tokens'])"
                        :input="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['prompt_tokens'])"
                        :output="\App\Helpers\Facades\HelperNumber::abbreviate($llmSummary['completion_tokens'])"/>
                <x-llm-analytics.stat-card
                        :label="__('components.ai.stats.requests_month')"
                        :value="\App\Helpers\Facades\HelperNumber::format($llmSummary['requests'], 0)"/>
            </div>
        @endif

        <div class="mt-4 flex flex-wrap gap-x-8 gap-y-2">
            <x-a :href="localized_route('ai.llm.index')" label="{{ __('components.ai.to_models') }} →"
                 class-attributes="inline-block text-base"/>
            @if ($llmSummary['requests'] > 0)
                <x-a :href="localized_route('ai.llm.analytics.index')" label="{{ __('components.ai.to_analytics') }} →"
                     class-attributes="inline-block text-base"/>
            @endif
        </div>
    </x-section>
</x-app-layout>
