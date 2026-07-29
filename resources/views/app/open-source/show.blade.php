<x-app-layout :page="$page" :schema="$schema">

    <x-layout.page-header :title="$name" :intro="$teaser" :breadcrumbs="[
        ['label' => __('Open Source'), 'url' => localized_route('open-source.index')],
        ['label' => $name],
    ]"/>

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
            <x-ui.button variant="outline" :href="$link" target="_blank" :label="__('View on GitHub')">
                {{ __('View on GitHub') }}
                <x-icon.external-link class="size-4"/>
            </x-ui.button>
        </x-layout.section>
    @endif

</x-app-layout>
