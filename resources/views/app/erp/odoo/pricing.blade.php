@php
    use App\Support\OdooPricing;

    $t = 'components.odoo_pricing.';
    $money = fn (int $cents): string => OdooPricing::format($cents);
    $label = fn (string $key, array $replace = []): string => __($t.'labels.'.$key, $replace);
    $max = $pricing['max_users'];
    $ticks = collect($pricing['tiers'])->pluck('up_to');
    $kinds = [
        'full' => ['min' => $pricing['full_users']['min'], 'max' => $max, 'value' => $result['full_users'], 'unit' => $result['full_unit']],
        'light' => ['min' => $pricing['light_users']['min'], 'max' => $max, 'value' => $result['light_users'], 'unit' => $result['light_unit']],
    ];
    $numberInput = 'odoo-number h-14 w-24 rounded-panel border border-border-strong bg-white text-center text-title font-bold text-gray-900 tabular-nums transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand';
    $stepButton = 'inline-flex size-12 shrink-0 cursor-pointer items-center justify-center rounded-full border border-brand bg-white text-2xl leading-none font-medium text-brand transition select-none hover:bg-brand hover:text-white focus-ring';
    $infoButton = 'inline-flex size-8 shrink-0 cursor-pointer items-center justify-center rounded-full text-muted transition hover:bg-brand/10 hover:text-brand focus-ring';
    $billingButton = 'inline-flex h-control flex-1 cursor-pointer items-center justify-center rounded-pill px-6 text-base font-medium text-gray-800 transition select-none hover:text-brand focus-ring aria-pressed:bg-brand aria-pressed:text-white aria-pressed:hover:text-white sm:flex-none';
@endphp

<x-app-layout :page="$page" :schema="$schema">

    <x-layout.page-header
            :title="__($t.'title')"
            :intro="__($t.'intro')"
            :breadcrumbs="[
                ['label' => __('Services'), 'url' => localized_route('services.index')],
                ['label' => __($t.'crumb')],
            ]">
        <x-slot:eyebrow>
            <x-ui.badge :label="__($t.'badge')"/>
        </x-slot:eyebrow>
    </x-layout.page-header>

    <div x-data="odooPricing" data-config="{{ json_encode($pricing) }}" data-labels="{{ json_encode($labels) }}"
         data-selection="{{ json_encode($selection->query()) }}" data-summary-url="{{ $summaryUrl }}"
         data-share-title="{{ __($t.'report.title') }}" data-share-label="{{ __($t.'share.button') }}" data-share-copied="{{ __($t.'share.copied') }}">

        <x-layout.section>
            <x-layout.section-header :title="__($t.'setup.title')" :intro="__($t.'setup.intro')"/>

            <div class="divide-y divide-border overflow-hidden rounded-panel border border-border-strong bg-white">
                <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div id="odoo-billing" class="text-subheading font-semibold text-gray-900">{{ __($t.'billing.label') }}</div>
                        <p class="mt-1 text-sm text-muted">{{ __($t.'billing.hint') }}</p>
                    </div>
                    <div class="flex shrink-0 gap-1 rounded-pill border border-border-strong bg-white p-1" role="group" aria-labelledby="odoo-billing">
                        <button type="button" x-on:click="setYearly" x-bind:aria-pressed="isYearly" aria-pressed="{{ $selection->billing === 'yearly' ? 'true' : 'false' }}"
                                class="{{ $billingButton }}">{{ __($t.'billing.yearly') }}</button>
                        <button type="button" x-on:click="setMonthly" x-bind:aria-pressed="isMonthly" aria-pressed="{{ $selection->billing === 'monthly' ? 'true' : 'false' }}"
                                class="{{ $billingButton }}">{{ __($t.'billing.monthly') }}</button>
                    </div>
                </div>

                @foreach($kinds as $kind => $field)
                    <div class="flex flex-col p-6">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <label for="odoo-{{ $kind }}" class="text-subheading font-semibold text-gray-900">{{ __($t.$kind.'.label') }}</label>
                                    <a href="#faq-{{ $kind }}" data-faq="faq-{{ $kind }}" x-on:click="openFaq"
                                       aria-label="{{ __($t.$kind.'.info') }}" class="{{ $infoButton }}">
                                        <x-icon.info/>
                                    </a>
                                </div>
                                <p class="mt-2 text-base text-gray-800">
                                    <span class="font-semibold text-gray-900 tabular-nums" x-text="{{ $kind }}Unit">{{ $money($field['unit']) }}</span>
                                    <span class="text-sm text-muted">{{ __($t.'setup.per_user') }}</span>
                                </p>
                            </div>

                            <div class="flex shrink-0 items-center justify-center gap-3">
                                <button type="button" data-kind="{{ $kind }}" data-step="-1" x-on:click="step"
                                        aria-label="{{ __($t.$kind.'.decrease') }}" class="{{ $stepButton }}">−</button>
                                <input type="number" id="odoo-{{ $kind }}" inputmode="numeric" data-kind="{{ $kind }}"
                                       min="{{ $field['min'] }}" max="{{ $field['max'] }}" step="1" value="{{ $field['value'] }}"
                                       x-model.number="{{ $kind }}" x-on:change="normalize"
                                       class="{{ $numberInput }}">
                                <button type="button" data-kind="{{ $kind }}" data-step="1" x-on:click="step"
                                        aria-label="{{ __($t.$kind.'.increase') }}" class="{{ $stepButton }}">+</button>
                            </div>
                        </div>

                        <div class="mt-6">
                            <input type="range" aria-label="{{ __($t.$kind.'.label') }}" x-ref="{{ $kind }}Range" data-kind="{{ $kind }}"
                                   min="{{ $field['min'] }}" max="{{ $field['max'] }}" step="1" value="{{ $field['value'] }}"
                                   x-model.number="{{ $kind }}" x-on:input="normalize"
                                   class="odoo-range">
                            <div class="mt-2 flex justify-between text-xs text-muted tabular-nums" aria-hidden="true">
                                <span>{{ $field['min'] }}</span>@foreach($ticks as $tick)<span>{{ $tick }}</span>@endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                @include('app.erp.odoo._costs', ['live' => true])
            </div>

            <x-layout.page-note>{{ __($t.'setup.note', ['max' => $max]) }}</x-layout.page-note>
        </x-layout.section>

        @include('app.erp.odoo._details', ['live' => true])
    </div>

    <x-layout.section>
        <x-layout.section-header :title="__($t.'included.title')" :intro="__($t.'included.intro')"/>

        <x-ui.panel class="p-6">
            <ul class="flex list-disc flex-col gap-2 pl-5 text-base">
                @foreach(trans($t.'included.items') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </x-ui.panel>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__($t.'excluded.title')"/>
        <p class="max-w-3xl text-muted">{{ __($t.'excluded.body') }}</p>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__($t.'faq.title')" id="fragen" class="scroll-mt-8"/>

        <div class="flex flex-col gap-3">
            @foreach($faq as $key => $entry)
                <x-ui.collapsible :title="$entry['question']" id="faq-{{ $key }}" class="scroll-mt-8">
                    <p class="text-base">{{ $entry['answer'] }}</p>
                </x-ui.collapsible>
            @endforeach
        </div>
    </x-layout.section>

    <x-band.cta-band :title="__($t.'cta.title')" :body="__($t.'cta.body')">
        <x-ui.button :href="localized_route('contact.index')" size="lg">
            {{ __($t.'cta.button') }}
            <x-icon.arrow-right class="size-4"/>
        </x-ui.button>
    </x-band.cta-band>

</x-app-layout>
