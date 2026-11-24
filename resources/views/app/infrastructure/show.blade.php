<x-app-layout :page="$page">
    <x-layout.page-header :title="$station['name']" :intro="$station['text']"
                          :breadcrumbs="[['label' => __('components.infrastructure.crumb'), 'url' => $index], ['label' => $station['name']]]">
        <x-slot:eyebrow>
            <p class="border-l-4 pl-3 text-base font-medium text-muted" style="border-color: {{ $layer['color'] }}">{{ $layer['name'] }}</p>
        </x-slot:eyebrow>
    </x-layout.page-header>

    <x-layout.section>
        <dl class="grid grid-cols-1 border-t border-border-soft md:grid-cols-[minmax(0,16rem)_minmax(0,1fr)]">
            <dt class="px-2 pt-4 text-muted md:border-b md:border-border-soft md:pb-4">{{ __('components.infrastructure.provider') }}</dt>
            <dd class="border-b border-border-soft px-2 pb-4 font-medium text-gray-900 md:pt-4">{{ $station['vendor'] }}</dd>

            <dt class="px-2 pt-4 text-muted md:border-b md:border-border-soft md:pb-4">{{ __('components.infrastructure.operation') }}</dt>
            <dd class="border-b border-border-soft px-2 pb-4 font-medium text-gray-900 md:pt-4">{{ $station['operation'] }}</dd>

            <dt class="px-2 pt-4 text-muted md:border-b md:border-border-soft md:pb-4">{{ __('components.infrastructure.processing') }}</dt>
            <dd class="border-b border-border-soft px-2 pb-4 font-medium text-gray-900 md:pt-4">
                @foreach($station['countries'] as $country)
                    <span class="flex items-center gap-2">
                        <x-infrastructure.flag :code="$country['code']" :name="$country['name']"/>
                        <span>{{ $loop->first && $station['place'] !== '' ? $station['place'].', ' : '' }}{{ $country['name'] }}</span>
                    </span>
                @endforeach
            </dd>

            @if($station['vendor_country'])
                <dt class="px-2 pt-4 text-muted md:border-b md:border-border-soft md:pb-4">{{ __('components.infrastructure.origin') }}</dt>
                <dd class="border-b border-border-soft px-2 pb-4 font-medium text-gray-900 md:pt-4">
                    <span class="flex items-center gap-2">
                        <x-infrastructure.flag :code="$station['vendor_country']['code']" :name="$station['vendor_country']['name']"/>
                        <span>{{ $station['vendor_country']['name'] }}</span>
                    </span>
                </dd>
            @endif
        </dl>

        @if($station['links'] !== [])
            <ul class="mt-6 flex list-none flex-col gap-2">
                @foreach($station['links'] as $link)
                    <li>
                        <x-ui.link :href="$link['url']" target="_blank"
                                   class="flex flex-col gap-0.5 rounded-panel border border-border px-4 py-3 hover:bg-surface">
                            <span class="font-medium text-gray-900">↗ {{ $link['label'] }}</span>
                            <span class="truncate text-base text-muted">{{ $link['host'] }}</span>
                        </x-ui.link>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-layout.section>

    <x-layout.section>
        <x-ui.button :href="$index" :label="__('components.infrastructure.cta')" variant="outline"/>
    </x-layout.section>
</x-app-layout>
