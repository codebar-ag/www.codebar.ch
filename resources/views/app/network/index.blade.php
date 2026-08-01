<x-app-layout :page="$page">
    <x-layout.page-header
            :title="__('Network')"
            :intro="__('Good software is never built alone. Our network is rooted in the open-source spirit and in partnerships that genuinely matter.')"/>

    @foreach($groups as $categoryValue => $networks)
        <x-layout.section>
            <x-layout.section-header :title="$networks->first()->category->getLabel()"/>

            <x-layout.grid gap="gap-6" class="mt-4">
                @foreach($networks as $network)
                    <div class="flex flex-col overflow-hidden rounded-panel border border-border">
                        <div class="relative hidden sm:flex h-20 items-center {{ $network->cover_url ? 'justify-start' : 'justify-center' }} border-b border-border-soft bg-surface px-4">
                            @php
                                $drawing = 'images/network/'.$network->key.'.svg';
                            @endphp
                            @if($network->cover_url)
                                <img src="{{ $network->cover_url }}" alt="{{ $network->name }}" loading="lazy" width="128" height="64" class="max-h-16 w-auto">
                            @elseif(file_exists(public_path($drawing)))
                                <img src="{{ asset($drawing) }}" alt="" aria-hidden="true"
                                     loading="lazy" width="160" height="96" class="max-h-16 w-auto">
                            @else
                                <img src="{{ asset('images/placeholders/network-company.svg') }}" alt="" aria-hidden="true"
                                     loading="lazy" width="160" height="96" class="max-h-12 w-auto opacity-70">
                            @endif

                            @if($network->tier_label)
                                <x-ui.badge :label="$network->tier_label" size="xs"
                                            :variant="str_contains($network->tier_label, 'Silver') ? 'metal' : 'default'"
                                            class="absolute bottom-2 right-2"/>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col gap-2 p-4">
                            <div class="flex flex-col gap-0">
                                <div class="flex min-w-0 items-center justify-between gap-2">
                                    <span class="truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $network->name }}</span>
                                    <x-ui.social-links :name="$network->name" class="-mr-2 shrink-0 sm:mr-0"
                                                       :links="['website' => $network->website]"
                                                       :titles="['website' => $network->websiteHost()]"/>
                                </div>

                                @if($network->excerpt)
                                    <span class="-mt-0.5 text-sm leading-tight text-muted">{{ $network->excerpt }}</span>
                                @endif
                            </div>

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
                               class="flex min-h-control items-center justify-between gap-2 bg-brand px-4 text-sm font-medium text-white transition hover:bg-brand-strong focus-ring-light">
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
