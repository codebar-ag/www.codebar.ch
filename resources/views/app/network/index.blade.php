<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Network')"
            :intro="__('Good software is not created alone. Our network is built on the open source spirit, on learning together as a community, and on partnerships that matter to us.')"/>

    @foreach($groups as $categoryValue => $networks)
        <x-layout.section>
            <x-layout.section-header :title="$networks->first()->category->getLabel()"/>

            <x-layout.grid gap="gap-6" class="mt-4">
                @foreach($networks as $network)
                    <div class="flex flex-col overflow-hidden rounded-panel border border-gray-200">
                        <div class="relative hidden sm:flex h-20 items-center justify-center border-b border-gray-100 bg-gray-50 px-4">
                            @if($network->logo)
                                <img src="{{ $network->logo }}" alt="{{ $network->name }}" loading="lazy" class="max-h-12 w-auto">
                            @else
                                <img src="{{ asset('images/placeholders/network-company.svg') }}" alt="" aria-hidden="true"
                                     loading="lazy" class="max-h-12 w-auto opacity-70">
                            @endif

                            @if($network->tier_label)
                                <span class="absolute bottom-2 right-2 inline-flex items-center rounded-pill bg-white/90 px-2 py-1 text-sm font-medium text-muted ring-1 ring-gray-400/20 ring-inset">
                                    {{ $network->tier_label }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col gap-2 p-4">
                            <div class="flex min-w-0 items-center justify-between gap-2">
                                <span class="truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $network->name }}</span>
                                @if($network->website)
                                    <a href="{{ $network->website }}" target="_blank" rel="noopener noreferrer"
                                       aria-label="{{ $network->websiteHost() }}"
                                       title="{{ $network->websiteHost() }}"
                                       class="flex size-7 shrink-0 items-center justify-center text-muted hover:text-gray-800">
                                        <x-icon.website class="size-5"/>
                                    </a>
                                @endif
                            </div>

                            @if($network->tier_label || $network->excerpt)
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($network->tier_label)
                                        <span class="sm:hidden">
                                            <x-ui.badge :label="$network->tier_label"/>
                                        </span>
                                    @endif
                                    @if($network->excerpt)
                                        <span class="text-sm text-muted">{{ $network->excerpt }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($network->publishedUsers->isNotEmpty())
                                <div class="mt-auto">
                                    @foreach($network->publishedUsers as $user)
                                        <x-card.network-user-card :user="$user"/>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if($network->page_slug)
                            <a href="{{ localized_route('network.show', ['slug' => $network->page_slug]) }}"
                               title="{{ __('More about :name', ['name' => $network->name]) }}"
                               class="flex items-center justify-between gap-2 bg-brand px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brand-strong">
                                {{ __('Learn more') }}
                                <x-icon.arrow-right class="size-4"/>
                            </a>
                        @endif
                    </div>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endforeach
</x-app-layout>
