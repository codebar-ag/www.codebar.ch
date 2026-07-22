@extends('demo._app_layout')

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
            <p class="text-lg text-zinc-500 max-w-xl mb-10">{{ $content['subheadline'] }}</p>

            {{-- Flow diagram illustration --}}
            <svg viewBox="0 0 640 140" class="w-full h-auto max-w-2xl" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="8" y="45" width="130" height="50" rx="3" stroke="#18181b" stroke-width="1.5"/>
                <text x="73" y="75" text-anchor="middle" font-family="ui-monospace, monospace" font-size="11" fill="#18181b">Dokument</text>

                <line x1="138" y1="70" x2="222" y2="70" stroke="#a1a1aa" stroke-width="1.5" stroke-dasharray="4 3"/>
                <polygon points="222,70 213,65.5 213,74.5" fill="#a1a1aa"/>

                <circle cx="280" cy="70" r="52" stroke="#500472" stroke-width="1.5"/>
                <text x="280" y="66" text-anchor="middle" font-family="ui-monospace, monospace" font-size="11" fill="#500472">Agent</text>
                <text x="280" y="80" text-anchor="middle" font-family="ui-monospace, monospace" font-size="9" fill="#500472" opacity="0.6">orchestriert</text>

                <line x1="332" y1="70" x2="416" y2="70" stroke="#a1a1aa" stroke-width="1.5" stroke-dasharray="4 3"/>
                <polygon points="416,70 407,65.5 407,74.5" fill="#a1a1aa"/>

                <rect x="416" y="45" width="216" height="50" rx="3" stroke="#18181b" stroke-width="1.5"/>
                <text x="480" y="75" font-family="ui-monospace, monospace" font-size="11" fill="#18181b">Geprüftes Ergebnis</text>
                <path d="M596 70l6 6 12-13" stroke="#059669" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
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
                    <div class="border-t border-zinc-200 pt-4">
                        <div class="flex items-center gap-2 text-zinc-400 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16m0 0-6-6m6 6-6 6"/>
                            </svg>
                        </div>
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
                    <div class="border-t border-zinc-200 pt-4">
                        <div class="text-xs font-mono text-zinc-400 mb-2">{{ sprintf('%02d', $i + 1) }}</div>
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
