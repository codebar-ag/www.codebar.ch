@extends('demo._layout')

@section('content')
    <main class="bg-white text-zinc-950">

        <section class="min-h-[90vh] flex flex-col justify-center px-6 md:px-16 border-b border-zinc-100">
            <div class="max-w-5xl">
                <h1 class="text-[13vw] md:text-8xl font-bold tracking-tighter leading-[0.95]">
                    {{ $content['headline'] }}
                </h1>
                <p class="text-xl md:text-2xl text-zinc-500 mt-8 max-w-2xl">{{ $content['subheadline'] }}</p>
            </div>
        </section>

        <section class="min-h-[70vh] flex flex-col justify-center px-6 md:px-16 border-b border-zinc-100 bg-zinc-950 text-white">
            <div class="max-w-4xl">
                <span class="text-sm font-mono text-white/40">01 / Problem</span>
                <h2 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] mt-4 mb-10">
                    {{ $content['problem']['heading'] }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-lg text-white/70 leading-relaxed">
                    <p>{{ $content['problem']['intro'] }} {{ $content['problem']['paragraphs'][0] }}</p>
                    <p>{{ $content['problem']['paragraphs'][1] }}</p>
                </div>
            </div>
        </section>

        <section class="min-h-[70vh] flex flex-col justify-center px-6 md:px-16 border-b border-zinc-100">
            <span class="text-sm font-mono text-zinc-400">02 / Plattform</span>
            <h2 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] mt-4 mb-6 max-w-4xl">
                {{ $content['features']['heading'] }}
            </h2>
            <p class="text-lg text-zinc-500 max-w-2xl mb-14">{{ $content['features']['intro'] }}</p>
            <div class="space-y-0 divide-y divide-zinc-100 border-t border-zinc-100">
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="grid grid-cols-1 md:grid-cols-[100px_1fr_2fr] gap-4 py-8 items-baseline">
                        <span class="text-3xl font-bold text-zinc-200">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="text-xl font-semibold">{{ $feature['title'] }}</h3>
                        <p class="text-zinc-500 leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="min-h-[60vh] flex flex-col justify-center px-6 md:px-16 border-b border-zinc-100 bg-[#500472] text-white">
            <span class="text-sm font-mono text-white/50">03 / Deployment</span>
            <h2 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] mt-4 mb-6 max-w-4xl">
                {{ $content['deployment']['heading'] }}
            </h2>
            <p class="text-lg text-white/70 max-w-2xl mb-14">{{ $content['deployment']['intro'] }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($content['deployment']['options'] as $option)
                    <div>
                        <h3 class="text-xl font-semibold mb-2">{{ $option['title'] }}</h3>
                        <p class="text-white/70 leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="min-h-[60vh] flex flex-col items-center justify-center text-center px-6">
            <h2 class="text-5xl md:text-7xl font-bold tracking-tight mb-6">{{ $content['cta']['heading'] }}</h2>
            <p class="text-xl text-zinc-500 mb-10 max-w-xl">{{ $content['cta']['body'] }}</p>
            <a href="#" class="inline-flex items-center gap-2 text-2xl font-semibold text-[#500472] border-b-2 border-[#500472] pb-1 hover:gap-4 transition-all">
                {{ $content['cta']['buttonLabel'] }} →
            </a>
        </section>

    </main>
@endsection
