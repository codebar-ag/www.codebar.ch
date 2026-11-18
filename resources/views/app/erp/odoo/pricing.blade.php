@php
    use App\Support\OdooPricing;

    $t = 'components.odoo_pricing.';
    $money = fn (int $cents): string => OdooPricing::format($cents);
    $label = fn (string $key, array $replace = []): string => __($t.'labels.'.$key, $replace);
    $max = $pricing['max_users'];
    $ticks = collect($pricing['tiers'])->pluck('up_to');
    $licenseShare = (int) round($result['license_month'] / $result['total_month'] * 100);
    $storageHint = $result['storage_capped'] === 1
        ? $label('storage_capped', ['max' => $pricing['storage_gb_max']])
        : $label('storage_hint', [
            'per' => $pricing['storage_gb_per_user'],
            'line' => $label('gigabytes', ['count' => $result['storage_gb']]).' × '.$money($result['storage_unit']),
        ]);
    $kinds = [
        'full' => ['min' => $pricing['full_users']['min'], 'value' => $result['full_users'], 'unit' => $result['full_unit']],
        'light' => ['min' => $pricing['light_users']['min'], 'value' => $result['light_users'], 'unit' => $result['light_unit']],
    ];
    $numberInput = 'odoo-number h-14 w-24 rounded-panel border border-border-strong bg-white text-center text-title font-bold text-gray-900 tabular-nums transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand';
    $stepButton = 'inline-flex size-12 shrink-0 cursor-pointer items-center justify-center rounded-full border border-brand bg-white text-2xl leading-none font-medium text-brand transition select-none hover:bg-brand hover:text-white focus-ring';
    $amount = 'py-3 pl-4 text-right align-top whitespace-nowrap tabular-nums';
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

    <div x-data="odooPricing" data-config="{{ json_encode($pricing) }}" data-labels="{{ json_encode($labels) }}">

        <x-layout.section>
            <x-layout.section-header :title="__($t.'setup.title')" :intro="__($t.'setup.intro')"/>

            <div class="flex flex-col gap-4">
                <x-ui.panel variant="plain" class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div id="odoo-billing" class="text-subheading font-semibold text-gray-900">{{ __($t.'billing.label') }}</div>
                        <p class="mt-1 text-sm text-muted">{{ __($t.'billing.hint') }}</p>
                    </div>
                    <div class="flex shrink-0 gap-1 rounded-pill border border-border-strong bg-white p-1" role="group" aria-labelledby="odoo-billing">
                        <button type="button" x-on:click="setYearly" x-bind:aria-pressed="isYearly" aria-pressed="true"
                                class="{{ $billingButton }}">{{ __($t.'billing.yearly') }}</button>
                        <button type="button" x-on:click="setMonthly" x-bind:aria-pressed="isMonthly" aria-pressed="false"
                                class="{{ $billingButton }}">{{ __($t.'billing.monthly') }}</button>
                    </div>
                </x-ui.panel>

                @foreach($kinds as $kind => $field)
                    <x-ui.panel variant="plain" class="flex flex-col p-6">
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
                                <input type="number" id="odoo-{{ $kind }}" inputmode="numeric"
                                       min="{{ $field['min'] }}" max="{{ $max }}" step="1" value="{{ $field['value'] }}"
                                       x-model.number="{{ $kind }}" x-on:change="normalize"
                                       class="{{ $numberInput }}">
                                <button type="button" data-kind="{{ $kind }}" data-step="1" x-on:click="step"
                                        aria-label="{{ __($t.$kind.'.increase') }}" class="{{ $stepButton }}">+</button>
                            </div>
                        </div>

                        <div class="mt-6">
                            <input type="range" aria-label="{{ __($t.$kind.'.label') }}" x-ref="{{ $kind }}Range"
                                   min="{{ $field['min'] }}" max="{{ $max }}" step="1" value="{{ $field['value'] }}"
                                   x-model.number="{{ $kind }}" x-on:input="normalize"
                                   class="odoo-range">
                            <div class="mt-2 flex justify-between text-xs text-muted tabular-nums" aria-hidden="true">
                                <span>{{ $field['min'] }}</span>@foreach($ticks as $tick)<span>{{ $tick }}</span>@endforeach
                            </div>
                        </div>
                    </x-ui.panel>
                @endforeach
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-between gap-x-6 gap-y-1 rounded-panel border border-border bg-surface px-6 py-4 text-base">
                <span>{{ __($t.'summary.total') }} <span class="font-semibold text-gray-900" x-text="peopleLabel">{{ $result['users'] === 1 ? $label('user_one') : $label('users', ['count' => $result['users']]) }}</span></span>
                <span class="text-muted">{{ __($t.'summary.storage') }} <span class="font-medium text-gray-800" x-text="storageLabel">{{ $label('gigabytes', ['count' => $result['storage_gb']]) }}</span></span>
                <span class="text-muted">{{ __($t.'summary.tier') }} <span class="font-medium text-gray-800" x-text="tierLabel">{{ $label('tier', ['count' => $result['tier_up_to']]) }}</span></span>
            </div>

            <x-ui.panel class="mt-4 border-brand p-6" role="status" aria-live="polite" aria-atomic="true">
                <h3 class="text-subheading font-semibold text-gray-900">{{ __($t.'total.title') }}</h3>

                <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-muted">{{ __($t.'total.per_year') }}</div>
                        <div class="mt-1 text-title font-bold text-brand tabular-nums" x-text="totalYear">{{ $money($result['total_month'] * 12) }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-muted">{{ __($t.'total.per_month') }}</div>
                        <div class="mt-1 text-title font-bold text-gray-900 tabular-nums" x-text="totalMonth">{{ $money($result['total_month']) }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-sm font-medium text-muted">{{ __($t.'total.split') }}</div>
                    <div class="mt-2 flex h-3 w-full overflow-hidden rounded-pill bg-white ring-1 ring-border ring-inset" aria-hidden="true">
                        <div x-ref="licenseBar" class="h-full bg-brand transition-[width] duration-300 {{ 'w-['.$licenseShare.'%]' }}"></div>
                        <div x-ref="hostingBar" class="h-full bg-brand/30 transition-[width] duration-300 {{ 'w-['.(100 - $licenseShare).'%]' }}"></div>
                    </div>
                    <dl class="mt-3 grid grid-cols-1 gap-2 text-base sm:grid-cols-2">
                        <div class="flex items-center justify-between gap-3 sm:justify-start">
                            <dt class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-brand" aria-hidden="true"></span>
                                {{ __($t.'total.licenses') }} <span class="text-sm text-muted" x-text="licenseShare">{{ $licenseShare }} %</span>
                            </dt>
                            <dd class="font-semibold text-gray-900 tabular-nums" x-text="licenseYear">{{ $money($result['license_month'] * 12) }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 sm:justify-start">
                            <dt class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-brand/30" aria-hidden="true"></span>
                                {{ __($t.'total.hosting') }} <span class="text-sm text-muted" x-text="hostingShare">{{ 100 - $licenseShare }} %</span>
                            </dt>
                            <dd class="font-semibold text-gray-900 tabular-nums" x-text="hostingYear">{{ $money($result['hosting_month'] * 12) }}</dd>
                        </div>
                    </dl>
                </div>
            </x-ui.panel>

            <x-layout.page-note>{{ __($t.'setup.note', ['max' => $max]) }}</x-layout.page-note>
        </x-layout.section>

        <x-layout.section>
            <x-h2 :title="__($t.'details.title')"/>
            <p class="mb-4 text-muted">
                {{ __($t.'details.intro') }}
                <span x-text="billingNote">{{ $label('note_yearly') }}</span>
            </p>

            <div class="flex flex-col gap-4">

                <x-ui.panel variant="plain" class="p-6">
                    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <h3 class="text-subheading font-semibold text-gray-900">{{ __($t.'licenses.title') }}</h3>
                        <x-ui.badge variant="notice" size="xs" :label="__($t.'licenses.badge')"/>
                    </div>
                    <p class="mt-1 text-base text-muted">{{ __($t.'licenses.intro') }}</p>

                    <table class="mt-4 w-full text-base">
                        <thead>
                        <tr class="border-b border-border text-sm text-muted">
                            <th scope="col" class="py-2 text-left font-medium">{{ __($t.'details.item') }}</th>
                            <th scope="col" class="py-2 pl-4 text-right font-medium">{{ __($t.'details.month') }}</th>
                            <th scope="col" class="py-2 pl-4 text-right font-medium">{{ __($t.'details.year') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b border-border-soft">
                            <th scope="row" class="py-3 text-left font-medium text-gray-900">
                                {{ __($t.'full.label') }}
                                <span class="block text-sm font-normal text-muted" x-text="fullLine">{{ $result['full_users'] }} × {{ $money($result['full_unit']) }}</span>
                            </th>
                            <td class="{{ $amount }}" x-text="fullMonth">{{ $money($result['full_month']) }}</td>
                            <td class="{{ $amount }}" x-text="fullYear">{{ $money($result['full_month'] * 12) }}</td>
                        </tr>
                        <tr x-ref="lightRow" class="{{ $result['light_users'] === 0 ? 'invisible' : '' }}">
                            <th scope="row" class="py-3 text-left font-medium text-gray-900">
                                {{ __($t.'light.label') }}
                                <span class="block text-sm font-normal text-muted" x-text="lightLine">{{ $result['light_users'] }} × {{ $money($result['light_unit']) }}</span>
                            </th>
                            <td class="{{ $amount }}" x-text="lightMonth">{{ $money($result['light_month']) }}</td>
                            <td class="{{ $amount }}" x-text="lightYear">{{ $money($result['light_month'] * 12) }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr class="border-t border-border">
                            <th scope="row" class="py-3 text-left font-semibold text-gray-900">{{ __($t.'licenses.total') }}</th>
                            <td class="{{ $amount }} font-semibold text-gray-900" x-text="licenseMonth">{{ $money($result['license_month']) }}</td>
                            <td class="{{ $amount }} font-semibold text-gray-900" x-text="licenseYear">{{ $money($result['license_month'] * 12) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </x-ui.panel>

                <x-ui.panel variant="plain" class="p-6">
                    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                        <h3 class="flex items-center gap-3 text-subheading font-semibold text-gray-900">
                            {{ __($t.'hosting.title') }}
                            <x-icon.flag-be class="h-4 w-6" :aria-label="__($t.'hosting.location')"/>
                        </h3>
                        <x-ui.badge variant="notice" size="xs" :label="__($t.'hosting.badge')"/>
                    </div>
                    <p class="mt-1 text-base text-muted">{{ __($t.'hosting.intro') }}</p>

                    <table class="mt-4 w-full text-base">
                        <thead>
                        <tr class="border-b border-border text-sm text-muted">
                            <th scope="col" class="py-2 text-left font-medium">{{ __($t.'details.item') }}</th>
                            <th scope="col" class="py-2 pl-4 text-right font-medium">{{ __($t.'details.month') }}</th>
                            <th scope="col" class="py-2 pl-4 text-right font-medium">{{ __($t.'details.year') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b border-border-soft">
                            <th scope="row" class="py-3 text-left font-medium text-gray-900">
                                {{ __($t.'hosting.operation') }}
                                <span class="block text-sm font-normal text-muted" x-text="operationHint">{{ $label('operation_hint', ['count' => $result['tier_up_to']]) }}</span>
                            </th>
                            <td class="{{ $amount }}" x-text="operationMonth">{{ $money($result['worker_month']) }}</td>
                            <td class="{{ $amount }}" x-text="operationYear">{{ $money($result['worker_month'] * 12) }}</td>
                        </tr>
                        <tr class="border-b border-border-soft">
                            <th scope="row" class="py-3 text-left font-medium text-gray-900">
                                <span x-text="storageTitle">{{ $label('storage_title', ['count' => $result['storage_gb']]) }}</span>
                                <span class="block text-sm font-normal text-muted" x-text="storageHint">{{ $storageHint }}</span>
                            </th>
                            <td class="{{ $amount }}" x-text="storageMonth">{{ $money($result['storage_month']) }}</td>
                            <td class="{{ $amount }}" x-text="storageYear">{{ $money($result['storage_month'] * 12) }}</td>
                        </tr>
                        <tr class="border-b border-border">
                            <th scope="row" class="py-3 text-left font-medium text-gray-900">
                                {{ __($t.'hosting.staging') }}
                                <span class="block text-sm font-normal text-muted">{{ __($t.'hosting.staging_hint') }}</span>
                            </th>
                            <td class="{{ $amount }}" x-text="stagingMonth">{{ $money($result['staging_month']) }}</td>
                            <td class="{{ $amount }}" x-text="stagingYear">{{ $money($result['staging_month'] * 12) }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="row" class="py-3 text-left font-semibold text-gray-900">{{ __($t.'hosting.total') }}</th>
                            <td class="{{ $amount }} font-semibold text-gray-900" x-text="hostingMonth">{{ $money($result['hosting_month']) }}</td>
                            <td class="{{ $amount }} font-semibold text-gray-900" x-text="hostingYear">{{ $money($result['hosting_month'] * 12) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </x-ui.panel>
            </div>

            <x-layout.page-note>
                {{ __($t.'details.source', ['date' => $checkedAt->format('d.m.Y')]) }}
                <x-ui.link href="{{ config('odoo-pricing.sources.license') }}" label="odoo.com" target="_blank" class="underline"/>
                {{ __($t.'details.source_and') }}
                <x-ui.link href="{{ config('odoo-pricing.sources.hosting') }}" label="odoo.sh" target="_blank" class="underline"/>.
            </x-layout.page-note>
        </x-layout.section>
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
