@props([
    'title',
    'subtitle' => null,
])

<div class="my-6">
    <p class="text-2xl font-bold text-white mb-2">{{ $title }}</p>

    @if($subtitle)
        <p class="text-white font-bold mb-4">
            {{ $subtitle }}
        </p>
    @endif
</div>

{{ $slot }}
