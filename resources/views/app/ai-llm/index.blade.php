@php
    $locale = app()->getLocale();
@endphp

<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai_llm.title')"/>
    <p class="text-gray-800">{{ __('components.ai_llm.intro') }}</p>

    @foreach ($groups as $category => $models)
        @php
            $categoryEnum = \App\Enums\AiModelCategoryEnum::from($category);
        @endphp
        <x-ai-llm.card>
            <x-h2 :title="$categoryEnum->title($locale)"/>
            <p class="text-gray-600 mb-4">{{ $categoryEnum->description($locale) }}</p>

            @foreach ($models as $model)
                <x-ai-llm.model-row :model="$model"/>
            @endforeach
        </x-ai-llm.card>
    @endforeach

    <div class="mt-8">
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
    </div>

    <x-ai-llm.card id="archiv">
        <x-h2 :title="__('components.ai_llm.archive.title')"/>
        <p class="text-gray-600 mb-4">{{ __('components.ai_llm.archive.intro') }}</p>

        @foreach ($archive as $category => $models)
            @php
                $categoryEnum = \App\Enums\AiModelCategoryEnum::from($category);
            @endphp
            <div class="{{ $loop->first ? '' : 'mt-8' }}">
                <x-h3 :title="$categoryEnum->title($locale)"/>
                <x-ai-llm.archive-table :models="$models"/>
            </div>
        @endforeach
    </x-ai-llm.card>
</x-app-layout>
