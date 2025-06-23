@props(['classAttributes' => "mt-2 grid grid-cols-1 lg:grid-cols-2 gap-4"])

<div class="{{ $classAttributes }}">
    {{ $slot }}
</div>