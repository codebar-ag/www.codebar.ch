<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai_llm.title')"/>
    <p class="text-gray-800">{{ __('components.ai_llm.intro') }}</p>

    @foreach ($groups as $group)
        <x-section>
            <x-ai-llm.card>
                <x-h2 :title="$group['category']->title()"/>
                <p class="text-gray-600 mb-4">{{ $group['category']->description() }}</p>

                @foreach ($group['models'] as $model)
                    <x-ai-llm.model-row :model="$model"/>
                @endforeach
            </x-ai-llm.card>
        </x-section>
    @endforeach

    <x-section>
        <x-h2 :title="__('components.ai_llm.infrastructure.title')"/>
        <p class="text-gray-600 mb-4">{{ __('components.ai_llm.infrastructure.intro') }}</p>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.hardware.label')"
                :text="__('components.ai_llm.infrastructure.items.hardware.text')"/>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.management.label')"
                :text="__('components.ai_llm.infrastructure.items.management.text')">
            <div class="mt-2 flex flex-wrap gap-2">
                <x-a-badge href="https://www.litellm.ai" label="LiteLLM ↗" target="_blank"
                           :title="__('components.ai_llm.tooltips.link')"/>
                <x-a-badge href="https://ollama.com" label="Ollama ↗" target="_blank"
                           :title="__('components.ai_llm.tooltips.link')"/>
            </div>
        </x-ai-llm.infra-row>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.access.label')"
                :text="__('components.ai_llm.infrastructure.items.access.text')"/>

        <x-ai-llm.infra-row
                :label="__('components.ai_llm.infrastructure.items.power.label')"
                :text="__('components.ai_llm.infrastructure.items.power.text')"/>
    </x-section>

    <x-section>
        <x-ai-llm.card id="archiv">
            <x-h2 :title="__('components.ai_llm.archive.title')"/>
            <p class="text-gray-600 mb-4">{{ __('components.ai_llm.archive.intro') }}</p>

            @foreach ($archive as $group)
                <div class="{{ $loop->first ? '' : 'mt-8' }}">
                    <x-h3 :title="$group['category']->title()"/>
                    <x-ai-llm.archive-table :models="$group['models']"/>
                </div>
            @endforeach
        </x-ai-llm.card>
    </x-section>
</x-app-layout>
