<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.ai_llm.title')" :intro="__('components.ai_llm.intro')"/>

    @foreach ($groups as $group)
        <x-layout.section>
            <x-ui.panel class="px-6 pt-6 pb-4">
                <x-layout.section-header :title="$group['category']->title()" :intro="$group['category']->description()"/>

                @foreach ($group['models'] as $model)
                    <x-ai-llm.model-row :model="$model"/>
                @endforeach
            </x-ui.panel>
        </x-layout.section>
    @endforeach

    <x-layout.section>
        <x-layout.section-header :title="__('components.ai_llm.infrastructure.title')" :intro="__('components.ai_llm.infrastructure.intro')"/>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.hardware.label')"
                :text="__('components.ai_llm.infrastructure.items.hardware.text')"/>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.management.label')"
                :text="__('components.ai_llm.infrastructure.items.management.text')">
            <div class="mt-2 flex flex-wrap gap-2">
                <x-ui.badge-link href="https://www.litellm.ai" label="LiteLLM" target="_blank"
                                 :title="__('components.ai_llm.tooltips.link')">
                    <x-icon.external-link class="ml-1 size-3"/>
                </x-ui.badge-link>
                <x-ui.badge-link href="https://ollama.com" label="Ollama" target="_blank"
                                 :title="__('components.ai_llm.tooltips.link')">
                    <x-icon.external-link class="ml-1 size-3"/>
                </x-ui.badge-link>
            </div>
        </x-ai-llm.infra-row>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.access.label')"
                :text="__('components.ai_llm.infrastructure.items.access.text')"/>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.power.label')"
                :text="__('components.ai_llm.infrastructure.items.power.text')"/>
    </x-layout.section>

    @if ($llmSummary['requests'] > 0)
        <x-layout.section>
            <x-layout.section-header :title="__('components.ai_llm.stats.title')" :intro="__('components.ai_llm.stats.intro')"/>

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

            <div class="mt-4">
                <x-ui.link :href="localized_route('ai.llm.analytics.index')" class="inline-block text-base">
                    {{ __('components.ai.to_analytics') }} <span aria-hidden="true">→</span>
                </x-ui.link>
            </div>
        </x-layout.section>
    @endif

    <x-layout.section>
        <x-ui.panel id="archiv" class="px-6 pt-6 pb-4">
            <x-layout.section-header :title="__('components.ai_llm.archive.title')" :intro="__('components.ai_llm.archive.intro')"/>

            @foreach ($archive as $group)
                <div class="{{ $loop->first ? '' : 'mt-8' }}">
                    <x-h3 :title="$group['category']->title()"/>
                    <x-ai-llm.archive-table :models="$group['models']" :caption="$group['category']->title()"/>
                </div>
            @endforeach
        </x-ui.panel>
    </x-layout.section>
</x-app-layout>
