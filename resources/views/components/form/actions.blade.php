{{-- The action row every form ends with. Stacked on a phone with the primary
     action on top (flex-col-reverse puts the last child first), side by side and
     right-aligned from sm up. Children stretch to full width on mobile without
     needing a single class of their own. --}}
<div {{ $attributes->merge(['class' => 'mt-section flex flex-col-reverse gap-3 sm:flex-row sm:justify-end']) }}>
    {{ $slot }}
</div>
