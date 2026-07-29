@props(['name', 'label' => null, 'help' => null])

{{-- One field pattern for the whole app: label → control → help → error, with the
     same vertical rhythm every time. Pass help as a prop for plain text or as a
     <x-slot:help> when it needs a link inside. --}}
<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    @if(filled($label))
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-800">{{ $label }}</label>
    @endif

    {{ $slot }}

    @if(filled($help))
        <p id="{{ $name }}-help" class="text-sm text-muted">{{ $help }}</p>
    @endif

    @error($name)
        {{-- The message itself carries the meaning; the red is only reinforcement. --}}
        <p id="{{ $name }}-error" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
