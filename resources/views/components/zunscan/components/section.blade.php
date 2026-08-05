@props(['tone' => 'paper'])

{{--
    The one vertical rhythm on the site. `tone` picks the surface: paper is the
    default canvas, blue is the accent band and must not appear more than once
    on a page besides the footer.
--}}
<div @class([
    'bg-paper bg-cover' => $tone === 'paper',
    'bg-paper-blue' => $tone === 'blue',
    'bg-white' => $tone === 'white',
])>
    <div {{ $attributes->merge(['class' => 'mx-auto max-w-5xl px-6 py-section']) }}>
        {{ $slot }}
    </div>
</div>
