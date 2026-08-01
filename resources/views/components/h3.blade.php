@props(['title'])

{{-- semibold, not bold: h2 above it is semibold, and a rung cannot be heavier than the one
     it hangs under. No tracking either — --text-subheading deliberately carries none, and
     this was the only heading on the site overriding its own token. --}}
<h3 {{ $attributes->merge(['class' => 'mb-4 text-subheading font-semibold text-balance text-gray-900']) }}>{{ $title }}</h3>
