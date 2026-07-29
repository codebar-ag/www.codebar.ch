@props(['href', 'label' => null, 'target' => '_self', 'title' => null, 'variant' => 'default'])

{{-- A badge that is a link. All styling lives in x-ui.badge — this exists so the
     intent still reads at the call site. --}}
<x-ui.badge :href="$href" :label="$label" :target="$target" :title="$title" :variant="$variant" {{ $attributes }}>
    {{ $slot }}
</x-ui.badge>
