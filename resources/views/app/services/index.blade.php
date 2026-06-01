<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Capabilities')"
        :title="__('Services')"
        :teaser="__('From strategy to engineering, our services are designed as practical capability stacks.')"
    />

    @foreach($services as $key => $group)
        <x-ui.section>
            <x-ui.eyebrow :text="__('Service Group')" />
            <h2 class="mt-3 text-3xl md:text-4xl font-semibold tracking-tight text-zinc-950 mb-12">{{ __($key) }}</h2>
            <x-ui.list>
                @foreach($group as $entry)
                    <x-list-card
                        :url="$entry->url ?? localized_route('services.show', ['locale' => app()->getLocale(), 'service' => $entry])"
                        :title="$entry->name"
                        :teaser="$entry->teaser"
                        :tags="$entry->tags"
                        target="{{ $entry->url ? '_blank' : '_self' }}"
                    />
                @endforeach
            </x-ui.list>
        </x-ui.section>
    @endforeach

    @include('app.services._partials.partnerships')
</x-app-layout>
