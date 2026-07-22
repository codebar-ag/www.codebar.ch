@extends('demo._app_layout')

@section('content')

    <div class="mb-16 -mt-2 p-8 md:p-10 border border-zinc-900 relative"
         style="background-image: linear-gradient(to right, #e4e4e7 1px, transparent 1px), linear-gradient(to bottom, #e4e4e7 1px, transparent 1px); background-size: 20px 20px;">
        {{-- corner marks --}}
        <svg class="absolute top-2 left-2 w-4 h-4 text-zinc-900" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 6V1h5"/></svg>
        <svg class="absolute top-2 right-2 w-4 h-4 text-zinc-900" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 6V1h-5"/></svg>
        <svg class="absolute bottom-2 left-2 w-4 h-4 text-zinc-900" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 10v5h5"/></svg>
        <svg class="absolute bottom-2 right-2 w-4 h-4 text-zinc-900" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 10v5h-5"/></svg>

        <div class="text-xs font-mono text-zinc-500 mb-6">FIG. 01 — FLOWS / SYSTEMÜBERSICHT</div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6 max-w-2xl">{{ $content['headline'] }}</h1>
        <p class="text-lg text-zinc-600 max-w-xl mb-6">{{ $content['subheadline'] }}</p>

        {{-- dimension line --}}
        <div class="flex items-center gap-2 max-w-md text-zinc-500">
            <span class="text-xs font-mono">A</span>
            <span class="flex-1 border-t border-dashed border-zinc-400"></span>
            <span class="text-xs font-mono">5 Module · 3 Deployments</span>
            <span class="flex-1 border-t border-dashed border-zinc-400"></span>
            <span class="text-xs font-mono">B</span>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-mono text-zinc-400">§01 PROBLEM</div>
        <div class="col-span-12 md:col-span-7">
            <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
            @foreach($content['problem']['paragraphs'] as $p)
                <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-mono text-zinc-400">§02 PLATTFORM</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['features']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['features']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="border border-zinc-300 p-5 relative">
                        <span class="absolute -top-2.5 left-3 bg-white px-1.5 text-xs font-mono text-zinc-400">2.{{ $i + 1 }}</span>
                        <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-mono text-zinc-400">§03 DEPLOYMENT</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['deployment']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['deployment']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                @foreach($content['deployment']['options'] as $i => $option)
                    <div class="border border-zinc-300 p-5 relative">
                        <span class="absolute -top-2.5 left-3 bg-white px-1.5 text-xs font-mono text-zinc-400">3.{{ $i + 1 }}</span>
                        <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-b border-zinc-900 py-10">
        <div class="col-span-12 md:col-span-2 text-xs font-mono text-zinc-400">§04 KONTAKT</div>
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
