@props(['title' => null, 'lines' => [], 'linkHref' => null, 'linkLabel' => null])

<div {{ $attributes }}>
    @if(filled($title))
        <x-h3 :title="$title"/>
    @endif

    <address class="not-italic leading-relaxed text-gray-800">
        @foreach($lines as $line)
            <p class="{{ $loop->first ? 'font-semibold' : 'font-light' }}">{{ $line }}</p>
        @endforeach
    </address>

    @if(filled($linkHref))
        <x-ui.badge-link :href="$linkHref" :label="$linkLabel" class="mt-1" target="_blank">
            <x-icon.external-link class="ml-1 size-3"/>
        </x-ui.badge-link>
    @endif
</div>
