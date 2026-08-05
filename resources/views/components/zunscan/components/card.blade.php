{{-- One radius and one shadow, defined once. Every white surface on the site
     is this component — cards that set their own rounding are how the old
     layout ended up with three different corner sizes on one page. --}}
<div {{ $attributes->merge(['class' => 'rounded-card bg-white p-6 shadow-card']) }}>
    {{ $slot }}
</div>
