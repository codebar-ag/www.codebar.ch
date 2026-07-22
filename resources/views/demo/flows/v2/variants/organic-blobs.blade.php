@extends('demo._app_layout')

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16 relative isolate">
        <div class="absolute -top-10 right-0 -z-10 w-72 h-72 opacity-40 blur-2xl"
             style="background: linear-gradient(135deg, #c026d3, #500472); border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
            <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16 relative isolate">
        <div class="absolute top-10 left-1/4 -z-10 w-56 h-56 opacity-25 blur-2xl"
             style="background: linear-gradient(135deg, #2563eb, #500472); border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;"></div>
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">01 — Problem</div>
        <div class="col-span-12 md:col-span-7">
            <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
            @foreach($content['problem']['paragraphs'] as $p)
                <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16 relative isolate">
        <div class="absolute top-0 right-10 -z-10 w-96 h-64 opacity-20 blur-3xl"
             style="background: linear-gradient(135deg, #500472, #c026d3, #2563eb); border-radius: 50% 50% 40% 60% / 60% 40% 60% 40%;"></div>
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
                @foreach($content['deployment']['options'] as $option)
                    <div class="border-t border-zinc-200 pt-4">
                        <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-b border-zinc-900 py-10 relative isolate overflow-hidden">
        <div class="absolute -bottom-16 -left-10 -z-10 w-72 h-72 opacity-30 blur-2xl"
             style="background: linear-gradient(135deg, #c026d3, #2563eb); border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;"></div>
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
