@use(App\Enums\LocaleEnum;use Illuminate\Support\Facades\Config;use Illuminate\Support\Str)

@php
    $display = Config::get('seeder.seeder.paperflakes');

    $showme_url = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://showme.docuware.com/en-gb/interactive-tours',
        default => 'https://showme.docuware.com/de/interactive-tours',
    };

    $product_url = match (app()->getLocale()) {
        LocaleEnum::EN->value => 'https://www.paperflakes.ch/services/en_CH/dms-ecm-docuware',
        default => 'https://www.paperflakes.ch/dienstleistungen/de_CH/dms-ecm-docuware',
    };
@endphp

@if ($display)
    <div class="mt-6" style="--brand: {{ $configuration?->company_primary_color }};">
        <x-section class-attributes="relative isolate bg-gray-100 overflow-hidden">
            <div
                class="absolute -top-32 -left-20 -z-10 h-[30rem] w-[30rem] rounded-full bg-(--brand) opacity-10 blur-[120px]">
            </div>
            <section class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 p-6 md:p-12">
                <div class="w-full">
                    <x-h2 :title="__('components.docuware.showme.title')" />
                    <p class="mb-6">
                        {{ __('components.docuware.showme.teaser') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-2 text-center">
                        <x-button variant="primary" :href="$showme_url" target="_blank"
                            rel="noopener noreferrer" aria-label="Interaktive DocuWare-Tour starten">
                            {{ __('components.docuware.showme.buttons.discover_now') }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="ml-1 w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </x-button>

                        <x-button variant="outline" :href="$product_url" rel="noopener noreferrer"
                            :label="__('components.docuware.showme.buttons.more')" />
                    </div>
                </div>
            </section>
        </x-section>
    </div>
@endif
