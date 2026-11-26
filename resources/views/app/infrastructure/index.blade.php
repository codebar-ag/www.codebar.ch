<x-app-layout :page="$page">
    <x-layout.page-header :title="__('components.infrastructure.title')" :intro="__('components.infrastructure.intro')"
                          :breadcrumbs="[['label' => __('components.infrastructure.crumb')]]">
        <x-slot:meta>
            <x-ui.button type="button" data-infra-open size="lg" class="infra-open" :label="__('components.infrastructure.open_graphic')"/>
        </x-slot:meta>
    </x-layout.page-header>

    <x-layout.section>
        <x-h2 :title="__('components.infrastructure.list_title')"/>
        <p class="text-muted">{{ __('components.infrastructure.list_intro') }}</p>

        <div class="mt-6 flex flex-wrap gap-2" role="group" aria-label="{{ __('components.infrastructure.filter_label') }}">
            @php
                $chip = 'inline-flex cursor-pointer items-center gap-2 rounded-full border border-border px-4 py-2 text-base font-medium text-gray-900 focus-ring hover:bg-surface aria-pressed:border-gray-900 aria-pressed:bg-gray-900 aria-pressed:text-white';
            @endphp
            <button type="button" data-infra-filter-reset aria-pressed="true" class="{{ $chip }}">{{ __('components.infrastructure.filter_all') }}</button>
            @foreach($layers as $layer)
                <button type="button" data-infra-filter="{{ $layer['key'] }}" aria-pressed="false" class="{{ $chip }}">
                    <span class="size-2.5 rounded-full" style="background-color: {{ $layer['color'] }}" aria-hidden="true"></span>
                    {{ $layer['name'] }}
                    <span class="opacity-60">{{ count($layer['stations']) }}</span>
                </button>
            @endforeach
        </div>
    </x-layout.section>

    <x-infrastructure.list :layers="$layers" :urls="$urls"/>

    <dialog class="infra-overlay" id="infraOverlay" aria-label="{{ __('components.infrastructure.open_graphic') }}">
        <button type="button" class="infra-overlay-x" data-infra-close aria-label="{{ __('components.infrastructure.close') }}">×</button>
        <div class="infra-scroller" id="infraScroller">
            <div class="infra" id="infra">
                <section class="sx-scroll" id="sxScroll">
                    <div class="sx-stage" id="sxStage">
                        <div class="sx-scene" id="sxScene">
                            <div class="sx-view" id="sxView">
                                <div class="sx-world" id="sxWorld"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </dialog>

    <dialog class="infra-dialog" id="infraDialog" aria-labelledby="infraDialogTitle"></dialog>

    <div hidden id="infraFlags">
        @foreach(['CH', 'DE', 'SE', 'BE', 'BG', 'US', 'WW'] as $code)
            <span data-flag="{{ $code }}"><x-infrastructure.flag :code="$code" :name="__('components.infrastructure.countries.'.$code)"/></span>
        @endforeach
    </div>

    <script type="application/json" id="infraData">{!! json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) !!}</script>

    @vite(['resources/js/infrastructure.js'])
</x-app-layout>
