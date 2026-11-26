@props(['layers', 'urls'])

@php
    $row = 'flex w-full items-center justify-between gap-4 px-2 py-4 text-left focus-ring hover:bg-surface';
@endphp

@foreach($layers as $layer)
    <x-layout.section data-infra-layer="{{ $layer['key'] }}">
        <x-h2 :title="$layer['name']" id="infra-{{ $layer['key'] }}" class="scroll-mt-24 border-l-4 pl-3" style="border-color: {{ $layer['color'] }}"/>

        <ul class="list-none">
            @foreach($layer['stations'] as $station)
                <li class="border-t border-border-soft last:border-b">
                    <a href="{{ $urls[$station['id']] }}" class="{{ $row }}">
                        <span>
                            <span class="block font-semibold text-gray-900">{{ $station['name'] }}</span>
                            <span class="block text-base text-muted">{{ $station['provider'] }}</span>
                        </span>
                        <span class="flex shrink-0 gap-1">
                            @foreach($station['flags'] as $code)
                                <x-infrastructure.flag :code="$code" :name="__('components.infrastructure.countries.'.$code)"/>
                            @endforeach
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </x-layout.section>
@endforeach
