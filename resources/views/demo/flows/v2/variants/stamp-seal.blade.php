@extends('demo._app_layout')

@php
    $stampSvg = function (string $num, string $label): string {
        return '<svg viewBox="0 0 80 80" class="w-14 h-14 flex-shrink-0" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="38" fill="none" stroke="#18181b" stroke-width="1.2" stroke-dasharray="2.4 2.6"/>
            <circle cx="40" cy="40" r="30" fill="none" stroke="#500472" stroke-width="1.2"/>
            <text x="40" y="37" text-anchor="middle" font-family="ui-monospace, monospace" font-size="14" font-weight="700" fill="#18181b">'.$num.'</text>
            <text x="40" y="50" text-anchor="middle" font-family="ui-monospace, monospace" font-size="6" letter-spacing="1" fill="#500472">'.strtoupper($label).'</text>
        </svg>';
    };
@endphp

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 flex md:block">
            {!! $stampSvg('FL', 'Flows') !!}
        </div>
        <div class="col-span-12 md:col-span-10">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
            <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2">
            {!! $stampSvg('01', 'Problem') !!}
        </div>
        <div class="col-span-12 md:col-span-7">
            <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
            @foreach($content['problem']['paragraphs'] as $p)
                <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2">
            {!! $stampSvg('02', 'Plattform') !!}
        </div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['features']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['features']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                @foreach($content['features']['items'] as $feature)
                    <div class="border-t border-zinc-200 pt-4">
                        <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2">
            {!! $stampSvg('03', 'Deployment') !!}
        </div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['deployment']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['deployment']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                @foreach($content['deployment']['options'] as $option)
                    <div class="border-t border-zinc-200 pt-4">
                        <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-b border-zinc-900 py-10">
        <div class="col-span-12 md:col-span-2">
            {!! $stampSvg('04', 'Kontakt') !!}
        </div>
        <div class="col-span-12 md:col-span-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $content['cta']['heading'] }}</h2>
                <p class="text-zinc-500 max-w-md">{{ $content['cta']['body'] }}</p>
            </div>
            <a href="#" class="flex-shrink-0 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest border-b border-zinc-900 pb-1">
                {{ $content['cta']['buttonLabel'] }} →
            </a>
        </div>
    </div>

@endsection
