@props(['variant' => 'default'])

@php
    $classes = match ($variant) {
        'legal' => 'legal-prose prose prose-gray max-w-none text-gray-800 prose-p:my-0 prose-p:leading-relaxed prose-ul:mt-4 prose-ul:mb-0 prose-li:my-1.5 prose-li:leading-relaxed',
        // prose-md does not exist in @tailwindcss/typography (sm|base|lg|xl|2xl);
        // md:prose-lg is the step that actually matches the surrounding text-lg.
        default => 'prose prose-gray max-w-none md:prose-lg',
    };
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
