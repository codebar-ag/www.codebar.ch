@props(['classAttributes' => ''])

<div {{ $attributes->merge(['class' => "mx-auto w-full max-w-6xl {$classAttributes}"]) }}>
    {{ $slot }}
</div>
