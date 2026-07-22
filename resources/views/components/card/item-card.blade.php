@props(['url', 'title', 'teaser' => null, 'tags' => [], 'target' => '_self', 'level' => 3])

<a href="{{ $url }}"
   @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
   {{ $attributes->merge(['class' => 'group block py-4 transition hover:bg-gray-50/50 rounded']) }}>
    <div class="flex flex-col gap-1">
        <h{{ $level }} class="text-xl font-semibold text-gray-800 group-hover:text-brand">
            {{ $title }}
        </h{{ $level }}>

        @if(filled($teaser))
            <div class="text-muted">
                {{ $teaser }}
            </div>
        @endif

        <x-data.tag-list :tags="$tags" class="mt-1"/>

        <span class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-brand">
            {{ __('Learn more') }}
            <x-icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1"/>
        </span>
    </div>
</a>
