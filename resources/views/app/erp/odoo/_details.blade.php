@php
    use App\Support\OdooPricing;

    $t = 'components.odoo_pricing.';
    $money = fn (int $cents): string => OdooPricing::format($cents);
    $label = fn (string $key, array $replace = []): string => __($t.'labels.'.$key, $replace);
    $amount = 'py-3 pl-4 text-right align-top whitespace-nowrap tabular-nums';
    $month = 'hidden sm:table-cell';
    $live ??= false;
    $capped = $result['storage_capped'] === 1;
    $storageHint = $label('storage_hint', [
        'per' => $pricing['storage_gb_per_user'],
        'line' => $label('gigabytes', ['count' => $result['storage_gb']]).' × '.$money($result['storage_unit']),
    ]);
@endphp

<x-layout.section>
    <x-h2 :title="__($t.'details.title')"/>
    <p class="mb-4 text-muted">
        {{ __($t.'details.intro') }}
        <span x-text="billingNote">{{ $label($selection->billing === 'monthly' ? 'note_monthly' : 'note_yearly') }}</span>
    </p>

    <div class="flex flex-col gap-4">

        <x-ui.panel variant="plain" class="p-6">
            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                <h3 class="text-subheading font-semibold text-gray-900">{{ __($t.'licenses.title') }}</h3>
                <x-ui.badge variant="notice" size="xs" :href="config('odoo-pricing.sources.license')" target="_blank" :label="__($t.'licenses.badge')">
                    <x-icon.external-link class="ml-1 size-3"/>
                </x-ui.badge>
            </div>
            <p class="mt-1 text-base text-muted">{{ __($t.'licenses.intro') }}</p>

            <table class="mt-4 w-full table-fixed text-base">
                <thead>
                <tr class="border-b border-border text-sm text-muted">
                    <th scope="col" class="py-2 text-left font-medium">{{ __($t.'details.item') }}</th>
                    <th scope="col" class="{{ $month }} w-40 py-2 pl-4 text-right font-medium">{{ __($t.'details.month') }}</th>
                    <th scope="col" class="w-40 py-2 pl-4 text-right font-medium">{{ __($t.'details.year') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b border-border-soft">
                    <th scope="row" class="py-3 text-left font-medium text-gray-900">
                        {{ __($t.'full.label') }}
                        <span class="block text-sm font-normal text-muted" x-text="fullLine">{{ $result['full_users'] }} × {{ $money($result['full_unit']) }}</span>
                    </th>
                    <td class="{{ $month }} {{ $amount }}" x-text="fullMonth">{{ $money($result['full_month']) }}</td>
                    <td class="{{ $amount }}" x-text="fullYear">{{ $money($result['full_month'] * 12) }}</td>
                </tr>
                        @if($live || $result['light_users'] > 0)
                        <tr x-ref="lightRow" class="{{ $result['light_users'] === 0 ? 'invisible' : '' }}">
                    <th scope="row" class="py-3 text-left font-medium text-gray-900">
                        {{ __($t.'light.label') }}
                        <span class="block text-sm font-normal text-muted" x-text="lightLine">{{ $result['light_users'] }} × {{ $money($result['light_unit']) }}</span>
                    </th>
                    <td class="{{ $month }} {{ $amount }}" x-text="lightMonth">{{ $money($result['light_month']) }}</td>
                    <td class="{{ $amount }}" x-text="lightYear">{{ $money($result['light_month'] * 12) }}</td>
                </tr>
                        @endif
                </tbody>
                <tfoot>
                <tr class="border-t border-border">
                    <th scope="row" class="py-3 text-left font-semibold text-gray-900">{{ __($t.'licenses.total') }}</th>
                    <td class="{{ $month }} {{ $amount }} font-semibold text-gray-900" x-text="licenseMonth">{{ $money($result['license_month']) }}</td>
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
                <x-ui.badge variant="notice" size="xs" :href="config('odoo-pricing.sources.hosting')" target="_blank" :label="__($t.'hosting.badge')">
                    <x-icon.external-link class="ml-1 size-3"/>
                </x-ui.badge>
            </div>
            <p class="mt-1 text-base text-muted">
                {{ __($t.'hosting.intro') }}
                @if($live)
                    <a href="#faq-on_premises" data-faq="faq-on_premises" x-on:click="openFaq"
                       class="font-medium text-brand underline underline-offset-2 transition hover:text-brand-strong focus-ring">{{ __($t.'hosting.on_premises_link') }}</a>
                @endif
            </p>

            <table class="mt-4 w-full table-fixed text-base">
                <thead>
                <tr class="border-b border-border text-sm text-muted">
                    <th scope="col" class="py-2 text-left font-medium">{{ __($t.'details.item') }}</th>
                    <th scope="col" class="{{ $month }} w-40 py-2 pl-4 text-right font-medium">{{ __($t.'details.month') }}</th>
                    <th scope="col" class="w-40 py-2 pl-4 text-right font-medium">{{ __($t.'details.year') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-b border-border-soft">
                    <th scope="row" class="py-3 text-left font-medium text-gray-900">
                        {{ __($t.'hosting.operation') }}
                        <span class="block text-sm font-normal text-muted" x-text="operationHint">{{ $label('operation_hint', ['count' => $result['tier_up_to']]) }}</span>
                    </th>
                    <td class="{{ $month }} {{ $amount }}" x-text="operationMonth">{{ $money($result['worker_month']) }}</td>
                    <td class="{{ $amount }}" x-text="operationYear">{{ $money($result['worker_month'] * 12) }}</td>
                </tr>
                <tr class="border-b border-border-soft">
                    <th scope="row" class="py-3 text-left font-medium text-gray-900">
                        <span class="grid">
                            <span class="col-start-1 row-start-1" x-text="storageTitle">{{ $label('storage_title', ['count' => $result['storage_gb']]) }}</span>
                            <span class="invisible col-start-1 row-start-1" aria-hidden="true">{{ $label('storage_title', ['count' => $pricing['storage_gb_max']]) }}</span>
                        </span>
                        <span class="grid text-sm font-normal text-muted">
                            <span x-ref="storageHint" class="col-start-1 row-start-1 {{ $capped ? 'invisible' : '' }}" x-text="storageHint">{{ $storageHint }}</span>
                            <span x-ref="storageCapped" class="col-start-1 row-start-1 {{ $capped ? '' : 'invisible' }}">{{ $label('storage_capped', ['max' => $pricing['storage_gb_max']]) }}</span>
                        </span>
                    </th>
                    <td class="{{ $month }} {{ $amount }}" x-text="storageMonth">{{ $money($result['storage_month']) }}</td>
                    <td class="{{ $amount }}" x-text="storageYear">{{ $money($result['storage_month'] * 12) }}</td>
                </tr>
                <tr class="border-b border-border">
                    <th scope="row" class="py-3 text-left font-medium text-gray-900">
                        {{ __($t.'hosting.staging') }}
                        <span class="block text-sm font-normal text-muted">{{ __($t.'hosting.staging_hint') }}</span>
                    </th>
                    <td class="{{ $month }} {{ $amount }}" x-text="stagingMonth">{{ $money($result['staging_month']) }}</td>
                    <td class="{{ $amount }}" x-text="stagingYear">{{ $money($result['staging_month'] * 12) }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th scope="row" class="py-3 text-left font-semibold text-gray-900">{{ __($t.'hosting.total') }}</th>
                    <td class="{{ $month }} {{ $amount }} font-semibold text-gray-900" x-text="hostingMonth">{{ $money($result['hosting_month']) }}</td>
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
