@props([
    'illustration' => null,
    'side' => 'right',
])

@php
    $left = $side === 'left';
@endphp

{{-- The one row with a drawing beside it: /dienstleistungen and every news list render
     this, so the size of the drawing, how far it sits outside the frame, the gap it leaves
     the text and the rhythm between rows are decided once. They used to be decided twice,
     and the two pages drifted 8 px apart on the drawing and a whole breakpoint on the
     padding.

     What a caller brings is the drawing and which side it is on. Everything else is here.

     xl and up only. The drawing exists to use the empty outer margin the 60rem frame leaves
     on a wide screen; below that width there is no margin to break into, and squeezing it
     into the text column leaves the column too narrow to read. So a row is text alone until
     there is room to do it properly. --}}
{{-- illustration-row carries the vertical rhythm — and the row keeps the class with or
     without a drawing, so a list of rows spaces the same either way. See app.css, which
     also explains why the padding is not in this class string. --}}
<article {{ $attributes->merge(['class' => 'illustration-row relative']) }}>
    {{-- pr and pl are deliberately different numbers, and both are derived from the
         drawing: it is 168 px wide and sits 128 px past the text column, so it reaches 40 px
         back in and the padding has to clear that. They differ because what a reader sees is
         the gap from the last pixel of text, and a ragged right edge stops well short of its
         own column while a left edge starts flush against it — equal padding would not read
         as an equal gap. Resize the drawing and both numbers move with it. --}}
    <div @class([
        'xl:pr-14' => $illustration && ! $left,
        'xl:pl-18' => $illustration && $left,
    ])>
        {{ $slot }}
    </div>

    {{-- 32, not 24: the drawing belongs 96 px outside the page frame, and the row it hangs
         off ends at the text column — a lg gutter, 32 px, further in. The services list used
         to buy that gutter back with a negative-margin wrapper; measuring from the column
         instead puts the drawing in exactly the same place without one, and leaves the news
         list's divider lines where they belong. The drawing only exists from xl, where the
         gutter is always lg, so the one number holds.

         Position sits on the wrapper and the hover tilt on the image: the centring here is a
         transform too, and one would cancel the other if they shared an element. --}}
    @if($illustration)
        <span @class([
            'hidden xl:absolute xl:top-1/2 xl:block xl:-translate-y-1/2',
            'xl:-right-32' => ! $left,
            'xl:-left-32' => $left,
        ])>
            <img src="{{ $illustration }}" alt="" aria-hidden="true" loading="lazy" decoding="async"
                 width="344" height="344"
                 style="--tilt: {{ $left ? '12deg' : '-12deg' }}"
                 class="illustration-row__art h-auto w-42"/>
        </span>
    @endif
</article>
