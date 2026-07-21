@extends('demo._layout')

@section('content')
    <main class="bg-white text-zinc-900">
        <div class="max-w-5xl mx-auto px-6 py-20">

            <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-20">
                <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
                <div class="col-span-12 md:col-span-10">
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
                    <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-20">
                <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">01 — Problem</div>
                <div class="col-span-12 md:col-span-7">
                    <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
                    @foreach($content['problem']['paragraphs'] as $p)
                        <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
                    @endforeach
                </div>
                <div class="hidden md:block md:col-span-3"></div>
            </div>

            <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-20">
                <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">02 — Plattform</div>
                <div class="col-span-12 md:col-span-10">
                    <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['features']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['features']['intro'] }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        @foreach($content['features']['items'] as $i => $feature)
                            <div class="border-t border-zinc-200 pt-4">
                                <div class="text-xs font-mono text-zinc-400 mb-2">{{ sprintf('%02d', $i + 1) }}</div>
                                <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-20">
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

        </div>
    </main>
@endsection
