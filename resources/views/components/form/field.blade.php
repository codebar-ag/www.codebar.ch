@props(['name', 'label' => null, 'help' => null])

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if(filled($label))
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-800">{{ $label }}</label>
    @endif

    {{ $slot }}

    @if(filled($help))
        <p id="{{ $name }}-help" class="text-sm text-muted">{{ $help }}</p>
    @endif

    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
