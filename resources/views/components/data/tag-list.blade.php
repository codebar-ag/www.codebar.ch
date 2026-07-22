@props(['tags' => []])

@php $tags = collect($tags); @endphp

@if($tags->isNotEmpty())
    <ul {{ $attributes->merge(['class' => 'flex flex-wrap gap-2']) }}>
        @foreach($tags as $tag)
            <li>
                <x-ui.badge :label="$tag" class="text-xs"/>
            </li>
        @endforeach
    </ul>
@endif
