@props(['label', 'value', 'input' => null, 'output' => null])

<div class="border border-gray-200 rounded-xl bg-gray-50 px-6 py-4 flex items-center justify-between gap-4">
    <div>
        <div class="text-2xl font-semibold text-gray-800">{{ $value }}</div>
        <div class="mt-1 text-sm text-gray-600">{{ $label }}</div>
    </div>
    @if ($input !== null || $output !== null)
        <div class="text-right shrink-0">
            <div class="text-sm text-gray-600">
                <span class="text-xs text-gray-500">{{ __('components.ai.stats.input') }}</span>
                <span class="font-medium text-gray-700">{{ $input }}</span>
            </div>
            <div class="mt-1 text-sm text-gray-600">
                <span class="text-xs text-gray-500">{{ __('components.ai.stats.output') }}</span>
                <span class="font-medium text-gray-700">{{ $output }}</span>
            </div>
        </div>
    @endif
</div>
