@props(['title'])

{{-- Size, line height and tracking come from the --text-display token, which is
     fluid, so no md: step is needed to shrink this on a phone. --}}
<h1 {{ $attributes->merge(['class' => 'mb-3 text-display font-bold text-balance']) }}>{{ $title }}</h1>
