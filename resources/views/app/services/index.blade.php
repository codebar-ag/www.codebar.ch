<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Services')"
        :teaser="__('From strategy to engineering, our services are designed as practical capability stacks.')"
        :eyebrow="__('Capabilities')"
    />

    @foreach($services as $key => $group)
        <x-ui.section class-attributes="editorial-divider">
            <x-ui.eyebrow text="{{ __('Service Group') }}" />
            <x-h2 :title="__($key)"/>
            <x-ui.section spacing="tight">
                <x-list>
                    @foreach($group as $entry)
                        <x-list-card
                                :url="$entry->url ?? localized_route('services.show',['locale' => app()->getLocale(),'service' => $entry])"
                                :title="$entry->name"
                                :teaser="$entry->teaser"
                                :tags="$entry->tags"
                                target="{{ $entry->url ? '_blank' : '_self' }}"/>
                    @endforeach
                </x-list>
            </x-ui.section>
        </x-ui.section>
    @endforeach

    @include('app.services._partials.partnerships')
</x-app-layout>