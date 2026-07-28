<x-app-layout :page="$page" :schema="$schema">

    <x-h1 :title="$name"/>
    <x-h1-teaser :teaser="$teaser"/>

    @if(filled($tags))
        <x-layout.section>
            <x-data.tag-list :tags="$tags"/>
        </x-layout.section>
    @endif

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>
    </x-layout.section>

    @if(filled($link))
        <x-layout.section>
            <x-ui.link :href="$link" target="_blank" label="{{ __('View on GitHub') }}"/>
        </x-layout.section>
    @endif

</x-app-layout>
