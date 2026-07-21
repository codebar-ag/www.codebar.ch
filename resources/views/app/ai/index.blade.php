<x-app-layout :page="$page">
    <x-h1 :title="__('components.ai.title')"/>
    <p class="text-gray-800">{{ __('components.ai.intro') }}</p>

    <div class="mt-8">
        <x-h2 :title="__('components.ai_llm.title')"/>
        <p class="text-gray-600">{{ __('components.ai.llm_teaser') }}</p>
        <x-a :href="localized_route('ai.llm.index')" label="{{ __('components.ai.more_info') }} →"
             classAttributes="mt-2 inline-block text-base"/>
    </div>
</x-app-layout>
