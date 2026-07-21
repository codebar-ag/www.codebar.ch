@extends('demo._app_layout')

@php
    $featureIcons = [
        '<rect x="5" y="3" width="13" height="18" rx="1"/><polyline points="8,13 10.5,15.5 16,9"/>',
        '<circle cx="7" cy="7" r="2"/><circle cx="17" cy="7" r="2"/><circle cx="12" cy="17" r="2"/><line x1="8.6" y1="8.2" x2="11" y2="15.2"/><line x1="15.4" y1="8.2" x2="13" y2="15.2"/><line x1="9" y1="7" x2="15" y2="7"/>',
        '<path d="M9 4c-2 0-3 1-3 3v2c0 1.2-1 2-2 2 1 0 2 .8 2 2v2c0 2 1 3 3 3"/><path d="M15 4c2 0 3 1 3 3v2c0 1.2 1 2 2 2-1 0-2 .8-2 2v2c0 2-1 3-3 3"/>',
        '<path d="M4 8h12l-3-3"/><path d="M20 16H8l3 3"/>',
        '<circle cx="12" cy="12" r="8"/><line x1="12" y1="12" x2="12" y2="7.5"/><line x1="12" y1="12" x2="15.3" y2="13.8"/>',
    ];

    $deploymentIcons = [
        '<path d="M4 11 12 4l8 7"/><path d="M6 10v9h12v-9"/><line x1="10" y1="19" x2="10" y2="14" /><line x1="14" y1="19" x2="14" y2="14" />',
        '<rect x="6" y="11" width="12" height="9" rx="1"/><path d="M9 11V8a3 3 0 0 1 6 0v3"/>',
        '<rect x="4" y="6" width="10" height="10" rx="1" stroke-dasharray="2.5 2.5"/><rect x="10" y="10" width="10" height="10" rx="1"/>',
    ];
@endphp

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
            <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">01 — Problem</div>
        <div class="col-span-12 md:col-span-7">
            <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
            @foreach($content['problem']['paragraphs'] as $p)
                <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">02 — Plattform</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['features']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['features']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-10">
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="border-t border-zinc-200 pt-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#500472" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 mb-3">
                            {!! $featureIcons[$i] !!}
                        </svg>
                        <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">03 — Deployment</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['deployment']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['deployment']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                @foreach($content['deployment']['options'] as $i => $option)
                    <div class="border-t border-zinc-200 pt-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#18181b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7 mb-3">
                            {!! $deploymentIcons[$i] !!}
                        </svg>
                        <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-b border-zinc-900 py-10">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">04 — Kontakt</div>
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
