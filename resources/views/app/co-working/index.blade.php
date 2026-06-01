<x-app-layout :page="$page">
    @php
        $fm = $item->frontmatter;
        $location = $fm['location'] ?? [];
        $gallery = $fm['gallery'] ?? [];
        $services = $fm['services'] ?? [];
        $pricing = $fm['pricing'] ?? null;
        $optionalServices = $fm['optional_services'] ?? [];
        $conditions = $fm['rental_conditions'] ?? [];
        $overview = isset($fm['overview']) ? \Illuminate\Support\Str::squish($fm['overview']) : null;
        $locationText = isset($fm['location_text']) ? \Illuminate\Support\Str::squish($fm['location_text']) : null;

        $iconPaths = [
            'desk' => 'M3.375 6.75h17.25M3.375 6.75v10.5a1.5 1.5 0 0 0 1.5 1.5h14.25a1.5 1.5 0 0 0 1.5-1.5V6.75M8.25 18.75v2.25M15.75 18.75v2.25',
            'shelf' => 'M3.75 4.5h16.5v15H3.75zM3.75 9.75h16.5M3.75 14.25h16.5',
            'chair' => 'M6.75 9V5.25A2.25 2.25 0 0 1 9 3h6a2.25 2.25 0 0 1 2.25 2.25V9M4.5 9h15v4.5a3 3 0 0 1-3 3H7.5a3 3 0 0 1-3-3V9zM7.5 16.5v3.75M16.5 16.5v3.75',
            'meeting' => 'M15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9A2.25 2.25 0 0 0 13.5 5.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25z',
            'key' => 'M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25z',
            'network' => 'M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0z',
            'server' => 'M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z',
            'mail' => 'M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75',
            'kitchen' => 'M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z',
            'printer' => 'M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z',
            'wardrobe' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.16 2.16 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008z',
            'cleaning' => 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09zM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456zM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423z',
            'support' => 'M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z',
        ];
    @endphp

    {{-- Hero --}}
    <x-ui.hero
        :title="$item->title"
        :teaser="$item->teaser"
    />

    {{-- Übersicht --}}
    @if(filled($overview))
        <x-ui.section>
            <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Overview') }}</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-zinc-950 text-balance md:text-4xl">
                {{ __('A focused workspace, fully equipped') }}
            </h2>
            <p class="mt-6 text-base leading-relaxed text-zinc-600 md:text-lg">{{ $overview }}</p>
        </x-ui.section>
    @endif

    {{-- Eindrücke / Impressions --}}
    @php
        $availableGallery = collect($gallery)
            ->filter(fn ($entry) => !empty($entry['image']) && file_exists(public_path(ltrim($entry['image'], '/'))))
            ->values();
        $hasGalleryImages = $availableGallery->isNotEmpty();
        $galleryConfig = ['images' => $availableGallery->map(fn ($entry) => [
            'image' => $entry['image'],
            'alt' => $entry['alt'] ?? '',
        ])->all()];
    @endphp
    <x-ui.section
        x-data="gallery"
        data-images="{{ json_encode($galleryConfig['images'], JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP) }}"
    >
        <x-ui.section-header
            :eyebrow="__('Impressions')"
            :title="__('A look inside')"
        />
        <div class="mt-12 grid grid-cols-3 gap-2 sm:grid-cols-3 md:grid-cols-4 md:gap-3">
            @if($hasGalleryImages)
                @foreach($availableGallery as $index => $entry)
                    <button
                        type="button"
                        x-on:click="open({{ $index }})"
                        class="group block overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
                        aria-label="{{ __('Open image') }}"
                    >
                        <img
                            src="{{ $entry['image'] }}"
                            alt="{{ $entry['alt'] ?? '' }}"
                            loading="lazy"
                            class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                        />
                    </button>
                @endforeach
            @else
                @foreach(range(1, 6) as $i)
                    <div class="flex aspect-[4/3] items-center justify-center rounded-lg border border-dashed border-zinc-300 bg-zinc-100 text-xs uppercase tracking-[0.2em] text-zinc-400">
                        {{ __('Image coming') }}
                    </div>
                @endforeach
            @endif
        </div>

        @if($hasGalleryImages)
            <div
                x-show="isOpen"
                x-cloak
                x-on:click.self="close()"
                class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/90 p-4 sm:p-8"
                role="dialog"
                aria-modal="true"
            >
                <button
                    type="button"
                    x-on:click.stop="close()"
                    class="absolute right-4 top-4 inline-flex size-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    aria-label="{{ __('Close') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
                <button
                    type="button"
                    x-show="hasMany"
                    x-on:click.stop="prev()"
                    class="absolute left-4 top-1/2 inline-flex size-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    aria-label="{{ __('Previous image') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.78 4.22a.75.75 0 0 1 0 1.06L7.06 11l5.72 5.72a.75.75 0 1 1-1.06 1.06l-6.25-6.25a.75.75 0 0 1 0-1.06l6.25-6.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button
                    type="button"
                    x-show="hasMany"
                    x-on:click.stop="next()"
                    class="absolute right-4 top-1/2 inline-flex size-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                    aria-label="{{ __('Next image') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.22 4.22a.75.75 0 0 1 1.06 0l6.25 6.25a.75.75 0 0 1 0 1.06l-6.25 6.25a.75.75 0 1 1-1.06-1.06L12.94 11 7.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <img
                    x-bind:src="activeImageSrc"
                    x-bind:alt="activeImageAlt"
                    class="max-h-full max-w-full rounded-lg object-contain shadow-2xl"
                />
            </div>
        @endif
    </x-ui.section>

    {{-- Standortvorteil Oberwil --}}
    <x-ui.section tone="muted">
        <div class="grid gap-x-12 gap-y-10 md:grid-cols-2 md:items-start">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Our location') }}</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-zinc-950 text-balance md:text-4xl">
                    {{ __('Why Oberwil') }}
                </h2>
                @if(filled($locationText))
                    <p class="mt-6 text-base leading-relaxed text-zinc-600 md:text-lg">{{ $locationText }}</p>
                @endif
            </div>
            <div>
                <div
                    data-leaflet-map
                    data-lat="{{ $location['lat'] ?? '' }}"
                    data-lng="{{ $location['lng'] ?? '' }}"
                    data-label="{{ $location['label'] ?? '' }}"
                    class="h-80 w-full overflow-hidden rounded-xl border border-zinc-200 bg-zinc-100 md:h-96"
                    aria-label="{{ __('Map of :address', ['address' => $location['label'] ?? '']) }}"
                ></div>
                @if(!empty($location))
                    <div class="mt-4 flex w-full flex-col gap-3 rounded-lg border border-zinc-200 bg-white px-4 py-3 text-sm leading-relaxed text-zinc-700 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <address class="flex flex-col not-italic">
                            <span class="font-semibold text-zinc-950">{{ config('site.company') }}</span>
                            <span>{{ $location['street'] ?? '' }}</span>
                            <span>{{ ($location['postal_code'] ?? '') }} {{ $location['city'] ?? '' }}</span>
                        </address>
                        @if(!empty($location['maps_url']))
                            <a
                                href="{{ $location['maps_url'] }}"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex shrink-0 items-center gap-1 text-sm text-zinc-600 underline decoration-zinc-300 underline-offset-4 hover:text-zinc-950 hover:decoration-zinc-950"
                            >
                                {{ __('Open in Google Maps') }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 0 0 1.06.053L16.5 4.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-9.056 8.194a.75.75 0 0 0-.053 1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </x-ui.section>

    {{-- Leistungen & Ausstattung --}}
    @if(!empty($services))
        @php
            $servicesByCategory = collect($services)->groupBy(fn ($service) => $service['category'] ?? __('Included'));
        @endphp
        <x-ui.section>
            <x-ui.section-header
                :eyebrow="__('Included')"
                :title="__('Everything that makes a workday work')"
            />
            <div class="mt-12 space-y-14">
                @foreach($servicesByCategory as $category => $items)
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ $category }}</p>
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($items as $service)
                                @php $iconPath = $iconPaths[$service['icon'] ?? ''] ?? null; @endphp
                                <article class="flex flex-col rounded-xl border border-zinc-200 bg-white p-6">
                                    @if($iconPath)
                                        <span class="inline-flex size-10 items-center justify-center rounded-lg bg-brand/10 text-brand">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="size-6" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                                            </svg>
                                        </span>
                                    @endif
                                    <h3 class="mt-5 text-base font-semibold tracking-tight text-zinc-950">{{ $service['title'] }}</h3>
                                    @if(!empty($service['teaser']))
                                        <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $service['teaser'] }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.section>
    @endif

    {{-- Preise --}}
    @if(!empty($pricing))
        <x-ui.section tone="muted">
            <x-ui.section-header
                align="center"
                :eyebrow="__('Pricing')"
                :title="__('All-inclusive from CHF :price / month', ['price' => number_format((float) $pricing['price_chf'], 0)])"
                :teaser="__('One price. One contract. Everything included — just bring your laptop.')"
            />
            <div class="mt-12 mx-auto max-w-xl">
                <x-blocks.pricing-card
                    :name="$pricing['name']"
                    :description="$pricing['description'] ?? null"
                    :priceChf="$pricing['price_chf']"
                    :period="$pricing['period'] ?? __('month')"
                    :badge="__('All-inclusive')"
                    :featured="true"
                />
            </div>

            <div class="mt-12 mx-auto max-w-3xl space-y-8">
                {{-- Optional services --}}
                @if(!empty($optionalServices))
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 md:p-8">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Optional add-ons') }}</p>
                        <h3 class="mt-3 text-2xl font-semibold tracking-tight text-zinc-950">{{ __('Tailor it to your setup') }}</h3>
                        <dl class="mt-6 divide-y divide-zinc-100">
                            @foreach($optionalServices as $service)
                                <div class="flex items-baseline justify-between gap-6 py-3">
                                    <dt class="text-sm font-medium text-zinc-950">{{ $service['name'] }}</dt>
                                    <dd class="text-sm text-zinc-600 text-right">{{ $service['price'] ?? __('On request') }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                {{-- Vertragskonditionen --}}
                @if(!empty($conditions))
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 md:p-8">
                        <p class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-500">{{ __('Rental conditions') }}</p>
                        <h3 class="mt-3 text-2xl font-semibold tracking-tight text-zinc-950">{{ __('Fair, transparent terms') }}</h3>
                        <dl class="mt-6 divide-y divide-zinc-100">
                            <div class="flex items-baseline justify-between gap-6 py-3">
                                <dt class="text-sm font-medium text-zinc-950">{{ __('Minimum term') }}</dt>
                                <dd class="text-sm text-zinc-600 text-right">{{ ($conditions['minimum_months'] ?? '—') }} {{ __('months') }}</dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-6 py-3">
                                <dt class="text-sm font-medium text-zinc-950">{{ __('Notice period') }}</dt>
                                <dd class="text-sm text-zinc-600 text-right">{{ ($conditions['notice_months'] ?? '—') }} {{ __('months') }}</dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-6 py-3">
                                <dt class="text-sm font-medium text-zinc-950">{{ __('Deposit') }}</dt>
                                <dd class="text-sm text-zinc-600 text-right">{{ $conditions['deposit_text'] ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                @endif
            </div>
        </x-ui.section>
    @endif

    {{-- Contact CTA --}}
    <x-blocks.contact-cta
        :title="__('Schedule a viewing')"
        :teaser="__('Arrange a no-obligation visit and experience your future workspace in person.')"
        :subject="$item->title"
        :email="false"
        :phone="false"
        :contactLabel="__('Book a viewing')"
        tone="muted"
        align="left"
    />
</x-app-layout>
