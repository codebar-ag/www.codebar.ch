<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Build digital products that feel crafted.')"
        :teaser="__('We design, engineer and scale web experiences with strong editorial clarity and Swiss quality.')"
        :eyebrow="__('Studio')"
        tone="default"
    >
        <x-ui.button :href="localized_route('services.index')" :label="__('Explore services')" />
        <x-ui.button :href="localized_route('about-us.index')" :label="__('Meet the team')" variant="outline" />
    </x-blocks.editorial-hero>

    <x-intro />

    @if($configuration?->section_news)
        <x-ui.section class-attributes="editorial-divider">
            <x-ui.eyebrow text="{{ __('Newsroom') }}" />
            <x-ui.headline :title="__('Latest News')" level="h2" class-attributes="mb-4 mt-2"/>
            <x-list>
                @foreach($news as $entry)
                    <x-list-card
                            :url="localized_route('news.show', ['locale' => app()->getLocale(),'news' => $entry])"
                            :title="$entry->title"
                            :teaser="$entry->teaser"
                            :tags="$entry->tags"/>
                @endforeach
            </x-list>
        </x-ui.section>
    @endif

    <x-docuware-showme/>

</x-app-layout>
