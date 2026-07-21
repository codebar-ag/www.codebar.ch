@extends('demo._layout')

@section('content')
    <main class="bg-zinc-100 text-zinc-900 min-h-screen">
        <div class="max-w-6xl mx-auto px-6 py-16">

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 auto-rows-[minmax(0,auto)]">

                {{-- HERO --}}
                <div class="md:col-span-4 md:row-span-2 rounded-3xl bg-[#500472] text-white p-10 flex flex-col justify-between overflow-hidden relative">
                    <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
                    <div>
                        <div class="text-sm font-medium text-white/60 uppercase tracking-wider mb-4">Flows</div>
                        <h1 class="text-4xl font-bold tracking-tight leading-tight">{{ $content['headline'] }}</h1>
                    </div>
                    <p class="text-white/80 mt-6 max-w-md">{{ $content['subheadline'] }}</p>
                </div>

                {{-- CTA tile --}}
                <div class="md:col-span-2 md:row-span-2 rounded-3xl bg-zinc-900 text-white p-8 flex flex-col justify-between">
                    <h2 class="text-2xl font-bold">{{ $content['cta']['heading'] }}</h2>
                    <div>
                        <p class="text-white/70 text-sm mb-4">{{ $content['cta']['body'] }}</p>
                        <a href="#" class="inline-flex items-center justify-center w-full px-4 py-3 rounded-xl bg-white text-zinc-900 font-semibold text-sm hover:bg-white/90 transition">
                            {{ $content['cta']['buttonLabel'] }}
                        </a>
                    </div>
                </div>

                {{-- Problem tile --}}
                <div class="md:col-span-3 rounded-3xl bg-white p-8">
                    <div class="text-xs font-semibold text-[#500472] uppercase tracking-wider mb-3">Das Problem</div>
                    <h3 class="text-xl font-bold mb-3 leading-snug">{{ $content['problem']['heading'] }}</h3>
                    <p class="text-zinc-600 text-sm leading-relaxed mb-2">{{ $content['problem']['intro'] }}</p>
                    <p class="text-zinc-600 text-sm leading-relaxed">{{ $content['problem']['paragraphs'][0] }}</p>
                </div>

                <div class="md:col-span-3 rounded-3xl bg-white p-8">
                    <p class="text-zinc-600 text-sm leading-relaxed">{{ $content['problem']['paragraphs'][1] }}</p>
                </div>

                {{-- Features intro tile --}}
                <div class="md:col-span-6 rounded-3xl bg-white p-8">
                    <div class="text-xs font-semibold text-[#500472] uppercase tracking-wider mb-3">Die Plattform</div>
                    <h3 class="text-2xl font-bold mb-3">{{ $content['features']['heading'] }}</h3>
                    <p class="text-zinc-600">{{ $content['features']['intro'] }}</p>
                </div>

                {{-- 5 feature tiles, varied sizes --}}
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="{{ in_array($i, [0, 3], true) ? 'md:col-span-3' : 'md:col-span-2' }} rounded-3xl bg-white p-6">
                        <h4 class="font-semibold mb-2">{{ $feature['title'] }}</h4>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach

                {{-- Deployment tile --}}
                <div class="md:col-span-6 rounded-3xl bg-zinc-900 text-white p-8">
                    <div class="text-xs font-semibold text-white/50 uppercase tracking-wider mb-3">Deployment</div>
                    <h3 class="text-2xl font-bold mb-2">{{ $content['deployment']['heading'] }}</h3>
                    <p class="text-white/70 mb-8 max-w-2xl">{{ $content['deployment']['intro'] }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($content['deployment']['options'] as $option)
                            <div class="rounded-2xl bg-white/5 p-5">
                                <h4 class="font-semibold mb-2">{{ $option['title'] }}</h4>
                                <p class="text-white/60 text-sm leading-relaxed">{{ $option['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
