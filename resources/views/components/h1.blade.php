@props(['title'])

{{-- Size, line height and tracking come from the --text-display token, which is
     fluid, so no md: step is needed to shrink this on a phone. --}}
{{-- mb-4, like h2 and h3: the gap under a heading is one number, and the element below
     adds nothing to it. The page header used to leave 12px above its lead while the
     article page left 16px, because the article added an mt-4 of its own. --}}
<h1 {{ $attributes->merge(['class' => 'mb-4 text-display font-bold text-balance text-gray-900']) }}>{{ $title }}</h1>
