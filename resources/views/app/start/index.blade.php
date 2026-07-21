<x-app-layout :page="$page">

    <x-intro/>

    <x-what-we-do/>

    <x-section>
        <x-list>
            <x-list-card
                    :url="localized_route('ai.llm.index')"
                    :title="__('components.ai_llm.title')"
                    :teaser="__('components.ai.llm_teaser')"/>
        </x-list>
    </x-section>


</x-app-layout>
