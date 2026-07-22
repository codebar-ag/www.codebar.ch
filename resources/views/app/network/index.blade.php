<x-app-layout :page="$page">
    <x-h1 :title="__('Network')"/>
    <p class="max-w-2xl text-gray-800">
        {{ __('Good software is not created alone. These companies and communities make our work better — and we make theirs.') }}
    </p>

    @foreach($groups as $categoryValue => $networks)
        <x-layout.section>
            <x-layout.section-header :title="$networks->first()->category->getLabel()"/>

            <x-layout.grid :cols="3" class="mt-4">
                @foreach($networks as $network)
                    <div class="flex flex-col overflow-hidden rounded-panel border border-gray-200">
                        <div class="flex h-20 items-center justify-center border-b border-gray-100 bg-gray-50 px-4">
                            @if($network->logo)
                                <img src="{{ $network->logo }}" alt="{{ $network->name }}" loading="lazy" class="max-h-12 w-auto">
                            @else
                                <span class="truncate whitespace-nowrap text-lg text-muted">{{ $network->name }}</span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col gap-2 p-4">
                            <div class="flex min-w-0 items-center justify-between gap-2">
                                <span class="truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $network->name }}</span>
                                @if($network->page_slug)
                                    <a href="{{ localized_route('network.show', ['slug' => $network->page_slug]) }}"
                                       aria-label="{{ __('More about :name', ['name' => $network->name]) }}"
                                       title="{{ __('More about :name', ['name' => $network->name]) }}"
                                       class="shrink-0 text-muted transition hover:text-brand">
                                        <x-icon.arrow-right class="size-5"/>
                                    </a>
                                @endif
                            </div>

                            @if($network->tier_label || $network->excerpt)
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($network->tier_label)
                                        <x-ui.badge :label="$network->tier_label"/>
                                    @endif
                                    @if($network->excerpt)
                                        <span class="text-sm text-muted">{{ $network->excerpt }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($network->website)
                                <a href="{{ $network->website }}" target="_blank" rel="noopener noreferrer"
                                   class="truncate whitespace-nowrap text-sm text-muted transition hover:text-brand">
                                    {{ $network->websiteHost() }} &nearr;
                                </a>
                            @endif

                            @foreach($network->publishedUsers as $user)
                                <x-card.network-user-card :user="$user"/>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endforeach
</x-app-layout>
