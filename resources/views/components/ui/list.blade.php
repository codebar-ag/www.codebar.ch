@props(['classAttributes' => ''])

<div {{ $attributes->merge(['class' => "ui-region grid grid-cols-1 gap-5 {$classAttributes}"]) }}>
    {{ $slot }}
</div>
