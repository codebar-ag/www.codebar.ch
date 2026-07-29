@props(['title'])

<h2 {{ $attributes->merge(['class' => 'mb-2 text-heading font-semibold text-balance']) }}>{{ $title }}</h2>
