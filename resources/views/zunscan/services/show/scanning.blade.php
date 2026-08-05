@php
    $extras = [
        [
            'title' => 'zunscan.services.scanning.disposal_title',
            'subtitle' => 'zunscan.services.scanning.disposal_subtitle',
            'rows' => [
                ['zunscan.services.scanning.disposal_price_label', 'zunscan.services.scanning.disposal_price'],
            ],
        ],
        [
            'title' => 'zunscan.services.scanning.return_title',
            'subtitle' => 'zunscan.services.scanning.return_subtitle',
            'rows' => [
                ['zunscan.services.scanning.return_folder_label', 'zunscan.services.scanning.return_folder_price'],
                ['zunscan.services.scanning.return_page_label', 'zunscan.services.scanning.return_page_price'],
            ],
        ],
        [
            'title' => 'zunscan.services.scanning.other_title',
            'subtitle' => 'zunscan.services.scanning.other_subtitle',
            'rows' => [
                ['zunscan.services.scanning.other_price_label', 'zunscan.services.scanning.other_price'],
            ],
        ],
    ];
@endphp

<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.services.scanning.title')"
                                :subtitle="__('zunscan.services.scanning.subtitle')"/>

    <x-zunscan.components.section>
        <x-zunscan.components.eyebrow>{{ __('zunscan.services.scanning.prices_eyebrow') }}</x-zunscan.components.eyebrow>
        <h2 class="mt-3 text-title text-zunscan-dark-gray">{{ __('zunscan.services.scanning.prices_title') }}</h2>

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

                <dl class="mt-4 space-y-2 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.folder_a4_price') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.disposal') }}</dt>
                        <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.folder_a4_disposal') }}</dd>
                    </div>
                </dl>
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

                <dl class="mt-4 space-y-2 border-t border-zunscan-light-blue/20 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.price') }}</dt>
                        <dd class="whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 font-bold text-white">{{ __('zunscan.services.scanning.page_a3a4_price') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="font-bold text-zunscan-dark-gray">{{ __('zunscan.common.disposal') }}</dt>
                        <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __('zunscan.services.scanning.page_a3a4_disposal') }}</dd>
                    </div>
                </dl>
            </x-zunscan.components.card>

            <x-zunscan.components.card class="sm:col-span-2 sm:flex sm:items-center sm:justify-between sm:gap-8">
                <h3 class="text-heading text-zunscan-dark-gray">{{ __('zunscan.services.scanning.flat_fee_title') }}</h3>
                <p class="mt-4 whitespace-nowrap rounded-card bg-zunscan-blue px-4 py-2 text-center font-bold text-white sm:mt-0">{{ __('zunscan.services.scanning.flat_fee_price') }}</p>
            </x-zunscan.components.card>
        </div>

        <h2 class="mt-16 text-title text-zunscan-dark-gray">{{ __('zunscan.services.scanning.more_services_title') }}</h2>

        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            @foreach($extras as $extra)
                <x-zunscan.components.card class="flex flex-col">
                    <h3 class="text-heading text-zunscan-dark-gray">{{ __($extra['title']) }}</h3>
                    <p class="mt-2 flex-grow font-light text-zunscan-light-gray">{{ __($extra['subtitle']) }}</p>

                    <dl class="mt-4 space-y-2 border-t border-zunscan-light-blue/20 pt-4">
                        @foreach($extra['rows'] as [$label, $price])
                            <div class="flex items-baseline justify-between gap-4">
                                <dt class="font-light text-zunscan-light-gray">{{ __($label) }}</dt>
                                <dd class="whitespace-nowrap font-bold text-zunscan-blue">{{ __($price) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </x-zunscan.components.card>
            @endforeach
        </div>
    </x-zunscan.components.section>

    <x-zunscan.patials.contactcta/>
</x-zunscan.layout>
