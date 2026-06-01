@props([
    'title' => null,
    'teaser' => null,
    'eyebrow' => null,
    'classAttributes' => '',
])

<x-ui.hero
    :title="$title"
    :teaser="$teaser"
    :eyebrow="$eyebrow"
    :class-attributes="$classAttributes"
>
    {{ $slot }}
</x-ui.hero>
