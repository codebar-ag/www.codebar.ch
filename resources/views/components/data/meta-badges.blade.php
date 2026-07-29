@props(['items' => []])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-y-2 md:flex-row md:items-center md:gap-x-2']) }}>
    @foreach($items as $title => $label)
        <x-ui.badge :label="$label" :title="$title" class="self-start"/>
    @endforeach
</div>
