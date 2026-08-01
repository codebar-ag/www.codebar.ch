<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.ai.title')" :intro="__('components.ai.intro')"/>

    <x-layout.section>
        <x-layout.section-header :title="__('components.ai_llm.title')" :intro="__('components.ai.llm_teaser')"/>

        @if ($llmSummary['requests'] > 0)
            <x-ai-llm.usage-summary :summary="$llmSummary"/>
        @endif

        <div class="mt-4 flex flex-wrap gap-x-8 gap-y-2">
            <x-ui.arrow-link :href="localized_route('ai.llm.index')" :label="__('components.ai.to_models')"/>
            @if ($hasUsage)
                <x-ui.arrow-link :href="localized_route('ai.llm.analytics.index')" :label="__('components.ai.to_analytics')"/>
            @endif
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('components.ai.local_title')"/>
        <x-ui.prose>
            <p>{{ __('components.ai.local_body') }}</p>
            <p>{{ __('components.ai.usage_body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    {{-- Every article tagged with the topic, in the same list the start page and the news
         index render — the block sits directly above «Mehr entdecken». --}}
    @if($news->isNotEmpty())
        <x-layout.section>
            <x-h2 :title="__('News')"/>

            <x-news.list :articles="$news"/>
        </x-layout.section>
    @endif
</x-app-layout>
