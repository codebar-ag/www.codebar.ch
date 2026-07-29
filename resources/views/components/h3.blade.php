@props(['title'])

<h3 {{ $attributes->merge(['class' => 'mb-3 text-subheading font-bold tracking-tight text-gray-950']) }}>{{ $title }}</h3>
