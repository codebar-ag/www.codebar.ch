{{-- The small uppercase kicker above a title. `uppercase` stays a utility —
     the text-* namespace cannot carry text-transform. --}}
<p {{ $attributes->merge(['class' => 'text-eyebrow uppercase text-zunscan-light-blue']) }}>
    {{ $slot }}
</p>
