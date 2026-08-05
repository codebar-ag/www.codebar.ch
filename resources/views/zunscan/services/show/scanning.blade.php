@php
    $tiers = [
        ['zunscan.services.scanning.tier_1_qty', 'zunscan.services.scanning.tier_1_price'],
        ['zunscan.services.scanning.tier_2_qty', 'zunscan.services.scanning.tier_2_price'],
        ['zunscan.services.scanning.tier_3_qty', 'zunscan.services.scanning.tier_3_price'],
        ['zunscan.services.scanning.tier_4_qty', 'zunscan.services.scanning.tier_4_price'],
    ];

    $setupIncludes = [
        'zunscan.services.scanning.setup_item_pickup',
        'zunscan.services.scanning.setup_item_trial',
        'zunscan.services.scanning.setup_item_structure',
    ];
@endphp

<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.services.scanning.title')"
                                :subtitle="__('zunscan.services.scanning.subtitle')"/>

    <x-zunscan.components.section>
        {{-- The trial scan sits above the tables on purpose: it is the offer that
             removes the risk of picking the wrong tariff further down. --}}
        <x-zunscan.components.card class="border-l-4 border-zunscan-light-blue sm:flex sm:items-center sm:justify-between sm:gap-8">
            <div>
                <p class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.trial_title') }}</p>
                <p class="mt-2 max-w-2xl font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.trial_body') }}</p>
            </div>

            <a href="{{ zunscan_route('contact.index') }}"
               class="mt-6 inline-flex min-h-control w-full shrink-0 items-center justify-center gap-2 rounded-card bg-zunscan-light-blue px-6 font-bold text-white transition hover:bg-zunscan-blue sm:mt-0 sm:w-auto">
                <span class="whitespace-nowrap">{{ __('zunscan.services.scanning.trial_cta') }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                </svg>
            </a>
        </x-zunscan.components.card>

        <div class="mt-16">
            <x-zunscan.components.eyebrow>{{ __('zunscan.services.scanning.prices_eyebrow') }}</x-zunscan.components.eyebrow>
            <h2 class="mt-3 text-title text-zunscan-dark-gray">{{ __('zunscan.services.scanning.prices_title') }}</h2>
            <p class="mt-3 font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.vat_note') }}</p>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            <x-zunscan.components.card class="flex flex-col">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.folder_a4_title') }}</h3>

                <div class="mt-4 flex-grow border-t border-zunscan-light-blue/20 pt-4">
                    <ol class="list-square space-y-1 pl-4 font-light text-zunscan-light-gray">
                        <li>{{ __('zunscan.services.scanning.folder_a4_pages') }}</li>
                        <li>
                            {{ __('zunscan.common.preparation') }}
                            <ul class="list-square pl-4">
                                <li>{{ __('zunscan.common.remove_staples') }}</li>
                                <li>{{ __('zunscan.common.remove_paperclips') }}</li>
                                <li>{{ __('zunscan.common.remove_tabs') }}</li>
                                <li>{{ __('zunscan.common.remove_sleeves') }}</li>
                            </ul>
                        </li>
                        <li>{{ __('zunscan.common.digitization_ocr') }}</li>
                    </ol>
                </div>

                {{-- The card carries only the entry price; the full tier scale
                     lives in a dialog. Inline, the four rows made this card far
                     taller than the one beside it and left a hole in the grid. --}}
                <div x-data="{ open: false }" x-on:keydown.escape.window="open = false"
                     class="mt-4 border-t border-zunscan-light-blue/20 pt-4">
                    <dl class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.tier_1_price') }}</dd>
                    </dl>

                    <button type="button" x-on:click="open = true"
                            class="mt-3 inline-flex min-h-control items-center gap-2 font-bold text-zunscan-blue hover:underline">
                        {{ __('zunscan.services.scanning.tier_open') }}
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-cloak role="dialog" aria-modal="true" aria-labelledby="tier-dialog-title"
                         class="fixed inset-0 z-50 flex items-end justify-center bg-zunscan-dark-blue/70 p-4 sm:items-center">
                        <div x-trap="open" x-on:click.outside="open = false"
                             class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-card bg-white p-6 shadow-card sm:p-10">
                            <div class="flex items-start justify-between gap-4">
                                <h4 id="tier-dialog-title" class="text-title text-zunscan-dark-gray">{{ __('zunscan.services.scanning.tier_dialog_title') }}</h4>

                                <button type="button" x-on:click="open = false"
                                        class="-m-2 grid size-control place-items-center rounded-card text-zunscan-light-gray transition hover:bg-zunscan-white">
                                    <span class="sr-only">{{ __('zunscan.services.scanning.close') }}</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- A description list rather than a <table>: two columns of
                                 key/value stack readably at 390px without a scroll container. --}}
                            <dl class="mt-6">
                                <div class="flex items-baseline justify-between gap-4 pb-3 text-eyebrow uppercase text-zunscan-light-gray">
                                    <dt>{{ __('zunscan.services.scanning.tier_quantity') }}</dt>
                                    <dd>{{ __('zunscan.services.scanning.tier_price') }}</dd>
                                </div>

                                @foreach($tiers as [$quantity, $price])
                                    <div class="flex items-baseline justify-between gap-4 border-t border-zunscan-light-blue/10 py-4">
                                        <dt class="font-light text-zunscan-light-gray">{{ __($quantity) }}</dt>
                                        <dd class="whitespace-nowrap text-heading text-zunscan-blue">{{ __($price) }}</dd>
                                    </div>
                                @endforeach
                            </dl>

                            <p class="mt-6 font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.tier_note') }}</p>
                        </div>
                    </div>
                </div>
            </x-zunscan.components.card>

            <x-zunscan.components.card class="flex flex-col">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.page_a3a4_title') }}</h3>

                <div class="mt-4 flex-grow border-t border-zunscan-light-blue/20 pt-4">
                    <ol class="list-square space-y-1 pl-4 font-light text-zunscan-light-gray">
                        <li>
                            {{ __('zunscan.common.preparation') }}
                            <ul class="list-square pl-4">
                                <li>{{ __('zunscan.common.remove_staples') }}</li>
                                <li>{{ __('zunscan.common.remove_paperclips') }}</li>
                                <li>{{ __('zunscan.common.remove_tabs') }}</li>
                                <li>{{ __('zunscan.common.remove_sleeves') }}</li>
                            </ul>
                        </li>
                        <li>{{ __('zunscan.common.digitization_ocr') }}</li>
                    </ol>
                </div>

                <dl class="mt-4 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.page_a3a4_price') }}</dd>
                    </div>
                </dl>
            </x-zunscan.components.card>

            {{-- Full width: with the starter package gone it is the only card on
                 its row, and a half-width box beside empty space reads as a bug. --}}
            <x-zunscan.components.card class="flex flex-col sm:col-span-2">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.setup_title') }}</h3>
                <p class="font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.setup_subtitle') }}</p>

                <div class="mt-4 flex-grow border-t border-zunscan-light-blue/20 pt-4">
                    <p class="text-eyebrow uppercase text-zunscan-light-gray">{{ __('zunscan.services.scanning.setup_includes') }}</p>
                    <ul class="mt-2 list-square space-y-1 pl-4 font-light text-zunscan-light-gray">
                        @foreach($setupIncludes as $item)
                            <li>{{ __($item) }}</li>
                        @endforeach
                    </ul>
                </div>

                <dl class="mt-4 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.setup_price') }}</dd>
                    </div>
                </dl>

                <p class="mt-4 text-sm font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.setup_note') }}</p>
            </x-zunscan.components.card>

        </div>

        <p class="mt-6 font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.vat_note') }}</p>

        <h2 class="mt-16 text-title text-zunscan-dark-gray">{{ __('zunscan.services.scanning.more_services_title') }}</h2>

        <div class="mt-6 grid gap-6 sm:grid-cols-2">
            <x-zunscan.components.card class="flex flex-col">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.disposal_title') }}</h3>
                <p class="mt-2 flex-grow font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.disposal_subtitle') }}</p>

                <dl class="mt-4 space-y-2 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.disposal_price') }}</dd>
                    </div>
                </dl>
            </x-zunscan.components.card>

            <x-zunscan.components.card class="flex flex-col">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.return_title') }}</h3>

                <div class="mt-4 flex-grow space-y-4 border-t border-zunscan-light-blue/20 pt-4">
                    <div>
                        <div class="flex items-baseline justify-between gap-4">
                            <p class="font-bold text-zunscan-dark-gray">{{ __('zunscan.services.scanning.return_loose_label') }}</p>
                            <p class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.return_loose_price') }}</p>
                        </div>
                        <p class="mt-1 text-sm font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.return_loose_body') }}</p>
                    </div>

                    <div class="border-t border-zunscan-light-blue/10 pt-4">
                        <div class="flex items-baseline justify-between gap-4">
                            <p class="font-bold text-zunscan-dark-gray">{{ __('zunscan.services.scanning.return_original_label') }}</p>
                            <p class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.return_original_price') }}</p>
                        </div>
                        <p class="mt-1 text-sm font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.return_original_body') }}</p>
                    </div>
                </div>

                <dl class="mt-4 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-baseline justify-between gap-4">
                        <dt class="font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.return_page_label') }}</dt>
                        <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.return_page_price') }}</dd>
                    </div>
                </dl>
            </x-zunscan.components.card>

            {{-- Full width: two rate lines make a short card, and it is the last
                 one in the grid — half width left a gap beside it. --}}
            <x-zunscan.components.card class="flex flex-col sm:col-span-2">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.hourly_title') }}</h3>

                {{-- One rate per row, full width — side by side the two labels
                     read as a single sentence broken across columns. --}}
                <dl class="mt-4 flex-grow border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-baseline justify-between gap-4 py-2">
                        <dt class="font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.hourly_production_label') }}</dt>
                        <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.hourly_production_price') }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 border-t border-zunscan-light-blue/10 py-2">
                        <dt class="font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.hourly_dms_label') }}</dt>
                        <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.hourly_dms_price') }}</dd>
                    </div>
                </dl>
            </x-zunscan.components.card>

        </div>

        <p class="mt-6 font-light text-zunscan-light-gray">{{ __('zunscan.services.scanning.vat_note') }}</p>
    </x-zunscan.components.section>

    <x-zunscan.patials.contactcta/>
</x-zunscan.layout>
