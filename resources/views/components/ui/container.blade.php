@props(['classAttributes' => ''])

<div {{ $attributes->merge(['class' => "mx-auto w-full max-w-6xl px-6 lg:px-8 {$classAttributes}"]) }}>
    {{ $slot }}
</div>
