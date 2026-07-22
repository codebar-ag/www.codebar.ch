<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai_llm.title')"/>
    <p class="text-gray-800">{{ __('components.ai_llm.intro') }}</p>

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
