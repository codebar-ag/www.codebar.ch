@extends('demo._app_layout')

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10 grid grid-cols-1 md:grid-cols-[1.3fr_1fr] gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
                <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
            </div>
            {{-- isometric stacked document sheets --}}
            <svg viewBox="0 0 200 180" class="w-full max-w-[220px] mx-auto h-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="20,110 100,70 180,110 100,150" fill="#f4f4f5" stroke="#18181b" stroke-width="1.5"/>
                <polygon points="20,90 100,50 180,90 100,130" fill="#fff" stroke="#18181b" stroke-width="1.5"/>
                <polygon points="20,70 100,30 180,70 100,110" fill="#faf5fb" stroke="#500472" stroke-width="1.5"/>
                <line x1="70" y1="42" x2="130" y2="42" stroke="#500472" stroke-width="1.5" opacity="0.5" transform="translate(0,8) skewX(0)"/>
                <circle cx="100" cy="50" r="4" fill="#500472"/>
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
                {{-- Own infra: isometric house --}}
                <div class="border-t border-zinc-200 pt-4">
                    <svg viewBox="0 0 100 90" class="w-16 h-16 mb-3" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="10,45 50,25 90,45 50,65" fill="#f4f4f5" stroke="#18181b" stroke-width="1.5"/>
                        <polygon points="10,45 50,65 50,80 10,60" fill="#fff" stroke="#18181b" stroke-width="1.5"/>
                        <polygon points="90,45 50,65 50,80 90,60" fill="#fafafa" stroke="#18181b" stroke-width="1.5"/>
                        <polygon points="25,36 50,23 50,10 25,23" fill="#faf5fb" stroke="#500472" stroke-width="1.5"/>
                    </svg>
                    <h3 class="font-semibold mb-1.5">{{ $content['deployment']['options'][0]['title'] }}</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">{{ $content['deployment']['options'][0]['description'] }}</p>
                </div>

                {{-- Dedicated: isolated cube with lock --}}
                <div class="border-t border-zinc-200 pt-4">
                    <svg viewBox="0 0 100 90" class="w-16 h-16 mb-3" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="15,35 50,17 85,35 50,53" fill="#f4f4f5" stroke="#18181b" stroke-width="1.5"/>
                        <polygon points="15,35 50,53 50,73 15,55" fill="#fff" stroke="#18181b" stroke-width="1.5"/>
                        <polygon points="85,35 50,53 50,73 85,55" fill="#fafafa" stroke="#18181b" stroke-width="1.5"/>
                        <rect x="41" y="58" width="18" height="14" rx="2" fill="#faf5fb" stroke="#500472" stroke-width="1.5"/>
                        <path d="M45 58v-4a5 5 0 0 1 10 0v4" stroke="#500472" stroke-width="1.5"/>
                    </svg>
                    <h3 class="font-semibold mb-1.5">{{ $content['deployment']['options'][1]['title'] }}</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">{{ $content['deployment']['options'][1]['description'] }}</p>
                </div>

                {{-- Shared: two cubes --}}
                <div class="border-t border-zinc-200 pt-4">
                    <svg viewBox="0 0 100 90" class="w-16 h-16 mb-3" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="5,45 35,30 65,45 35,60" fill="#fafafa" stroke="#a1a1aa" stroke-width="1.5" stroke-dasharray="3 3"/>
                        <polygon points="5,45 35,60 35,75 5,60" fill="#fff" stroke="#a1a1aa" stroke-width="1.5" stroke-dasharray="3 3"/>
                        <polygon points="35,25 65,10 95,25 65,40" fill="#faf5fb" stroke="#500472" stroke-width="1.5"/>
                        <polygon points="35,25 65,40 65,55 35,40" fill="#fff" stroke="#500472" stroke-width="1.5"/>
                        <polygon points="95,25 65,40 65,55 95,40" fill="#f3e8f7" stroke="#500472" stroke-width="1.5"/>
                    </svg>
                    <h3 class="font-semibold mb-1.5">{{ $content['deployment']['options'][2]['title'] }}</h3>
                    <p class="text-zinc-500 text-sm leading-relaxed">{{ $content['deployment']['options'][2]['description'] }}</p>
                </div>
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
