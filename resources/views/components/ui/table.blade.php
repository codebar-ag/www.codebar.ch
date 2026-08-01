@props(['caption' => null])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-sm [&_td:not(:last-child)]:pr-4 [&_th:not(:last-child)]:pr-4']) }}>
        @if(filled($caption))
            <caption class="sr-only">{{ $caption }}</caption>
        @endif

        {{ $slot }}
    </table>
</div>
