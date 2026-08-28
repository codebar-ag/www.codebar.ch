@props(['name', 'label' => null, 'help' => null, 'for' => null, 'required' => false])

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if(filled($label))
        <label for="{{ $for ?? $name }}" class="flex items-center gap-2 text-sm font-medium text-gray-800">
            {{ $label }}
            @if($required)
                <span class="rounded-pill bg-brand/10 px-2 py-0.5 text-xs font-semibold text-brand">{{ __('Required') }}</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if(filled($help))
        <p id="{{ $name }}-help" class="text-sm text-muted">{{ $help }}</p>
    @endif

    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
