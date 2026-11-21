@php
    use App\Support\OdooPricing;

    $t = 'components.odoo_pricing.';
    $money = fn (int $cents): string => OdooPricing::format($cents);
    $label = fn (string $key, array $replace = []): string => __($t.'labels.'.$key, $replace);
    $licenseShare = (int) round($result['license_month'] / $result['total_month'] * 100);
    $live ??= false;
@endphp

<div class="border-t-2 border-t-brand bg-brand/5 p-6" @if($live) role="status" aria-live="polite" aria-atomic="true" @endif>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h3 class="text-subheading font-semibold text-gray-900">{{ __($t.'total.title') }}</h3>
        @if($live)
            <x-ui.button type="button" variant="outline" size="sm" x-on:click="copy" data-copy-button>
                <x-icon.link class="size-4" x-show="notCopied"/>
                <x-icon.check class="size-4" x-show="copied" style="display: none"/>
                <span x-text="copyLabel">{{ __($t.'copy.button') }}</span>
            </x-ui.button>
        @endif
    </div>

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
            <div x-ref="licenseBar" class="h-full bg-brand transition-[width] duration-300" style="width: {{ $licenseShare }}%"></div>
            <div x-ref="hostingBar" class="h-full bg-brand/30 transition-[width] duration-300" style="width: {{ 100 - $licenseShare }}%"></div>
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
</div>

<div class="grid grid-cols-1 gap-x-6 gap-y-1 bg-surface px-6 py-4 text-base sm:grid-cols-3">
    <span>{{ __($t.'summary.total') }} <span class="font-semibold text-gray-900" x-text="peopleLabel">{{ $result['users'] === 1 ? $label('user_one') : $label('users', ['count' => $result['users']]) }}</span></span>
    <span class="text-muted sm:text-center">{{ __($t.'summary.storage') }} <span class="font-medium text-gray-800" x-text="storageLabel">{{ $label('gigabytes', ['count' => $result['storage_gb']]) }}</span></span>
    <span class="text-muted sm:text-right">{{ __($t.'summary.tier') }} <span class="font-medium text-gray-800" x-text="tierLabel">{{ $label('tier', ['count' => $result['tier_up_to']]) }}</span></span>
</div>
