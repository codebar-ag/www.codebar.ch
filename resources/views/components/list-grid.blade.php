@props(['classAttributes' => null])

<div class="{{ $classAttributes ?? 'mt-3 grid grid-cols-1 gap-6 lg:grid-cols-2' }}">
    {{ $slot }}
</div>
