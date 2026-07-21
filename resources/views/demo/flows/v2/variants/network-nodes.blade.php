@extends('demo._app_layout')

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10 grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
                <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
            </div>
            <svg viewBox="0 0 320 200" class="w-full h-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="160" y1="100" x2="50" y2="40" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="160" y1="100" x2="50" y2="160" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="160" y1="100" x2="270" y2="40" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="160" y1="100" x2="270" y2="160" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="50" y1="40" x2="160" y2="15" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="270" y1="40" x2="160" y2="15" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="50" y1="160" x2="160" y2="185" stroke="#d4d4d8" stroke-width="1.5"/>
                <line x1="270" y1="160" x2="160" y2="185" stroke="#d4d4d8" stroke-width="1.5"/>

                <circle cx="160" cy="100" r="12" fill="#500472"/>
                <circle cx="50" cy="40" r="7" fill="#fff" stroke="#500472" stroke-width="1.5"/>
                <circle cx="50" cy="160" r="7" fill="#fff" stroke="#500472" stroke-width="1.5"/>
                <circle cx="270" cy="40" r="7" fill="#fff" stroke="#500472" stroke-width="1.5"/>
                <circle cx="270" cy="160" r="7" fill="#fff" stroke="#500472" stroke-width="1.5"/>
                <circle cx="160" cy="15" r="4" fill="#a1a1aa"/>
                <circle cx="160" cy="185" r="4" fill="#a1a1aa"/>
            </svg>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                @foreach($content['features']['items'] as $feature)
                    <div class="border-t border-zinc-200 pt-4 flex gap-3">
                        <svg viewBox="0 0 20 20" class="w-5 h-5 flex-shrink-0 mt-1">
                            <line x1="10" y1="10" x2="3" y2="4" stroke="#d4d4d8" stroke-width="1.5"/>
                            <line x1="10" y1="10" x2="17" y2="4" stroke="#d4d4d8" stroke-width="1.5"/>
                            <line x1="10" y1="10" x2="10" y2="18" stroke="#d4d4d8" stroke-width="1.5"/>
                            <circle cx="10" cy="10" r="3" fill="#500472"/>
                            <circle cx="3" cy="4" r="1.5" fill="#a1a1aa"/>
                            <circle cx="17" cy="4" r="1.5" fill="#a1a1aa"/>
                            <circle cx="10" cy="18" r="1.5" fill="#a1a1aa"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                            <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                        </div>
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
