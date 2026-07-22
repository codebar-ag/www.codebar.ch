@extends('demo._layout')

@section('content')
    <main class="bg-white text-zinc-900">

        {{-- HERO --}}
        <section class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(192,38,211,0.18),transparent),radial-gradient(ellipse_60%_50%_at_90%_10%,rgba(37,99,235,0.14),transparent)]"></div>
            <div class="max-w-5xl mx-auto px-6 pt-28 pb-24 text-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-zinc-200 px-4 py-1.5 text-sm text-zinc-600 mb-8">
                    <span class="h-2 w-2 rounded-full bg-[#500472]"></span>
                    Neu: Flows
                </div>
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight leading-[1.05] mb-6">
                    {{ $content['headline'] }}
                </h1>
                <p class="text-xl text-zinc-600 max-w-2xl mx-auto mb-10">
                    {{ $content['subheadline'] }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="#cta" class="inline-flex items-center justify-center px-7 py-3.5 rounded-lg text-white font-semibold bg-[#500472] hover:bg-[#3a0354] transition shadow-lg shadow-[#500472]/20">
                        {{ $content['cta']['buttonLabel'] }}
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-7 py-3.5 rounded-lg font-semibold text-zinc-700 border border-zinc-300 hover:border-zinc-400 transition">
                        Mehr erfahren
                    </a>
                </div>
            </div>
        </section>

        {{-- PROBLEM --}}
        <section class="border-t border-zinc-100 bg-zinc-50/60">
            <div class="max-w-5xl mx-auto px-6 py-24">
                <div class="grid grid-cols-1 md:grid-cols-[1fr_1.4fr] gap-12 items-start">
                    <div>
                        <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Das Problem</span>
                        <h2 class="text-3xl font-bold tracking-tight mt-3 leading-tight">{{ $content['problem']['heading'] }}</h2>
                    </div>
                    <div class="space-y-5 text-lg text-zinc-600 leading-relaxed">
                        <p class="font-medium text-zinc-800">{{ $content['problem']['intro'] }}</p>
                        @foreach($content['problem']['paragraphs'] as $p)
                            <p>{{ $p }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES --}}
        <section id="features" class="max-w-5xl mx-auto px-6 py-24">
            <div class="max-w-2xl mb-16">
                <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Die Plattform</span>
                <h2 class="text-3xl font-bold tracking-tight mt-3 mb-4">{{ $content['features']['heading'] }}</h2>
                <p class="text-lg text-zinc-600">{{ $content['features']['intro'] }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-10">
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 h-9 w-9 rounded-lg bg-[#500472]/10 text-[#500472] font-bold flex items-center justify-center text-sm">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1.5">{{ $feature['title'] }}</h3>
                            <p class="text-zinc-600 leading-relaxed">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- DEPLOYMENT --}}
        <section class="border-t border-zinc-100 bg-zinc-50/60">
            <div class="max-w-5xl mx-auto px-6 py-24">
                <div class="max-w-2xl mb-12">
                    <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Deployment</span>
                    <h2 class="text-3xl font-bold tracking-tight mt-3 mb-4">{{ $content['deployment']['heading'] }}</h2>
                    <p class="text-lg text-zinc-600">{{ $content['deployment']['intro'] }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($content['deployment']['options'] as $option)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-zinc-100">
                            <h3 class="font-semibold text-lg mb-2">{{ $option['title'] }}</h3>
                            <p class="text-zinc-600 text-sm leading-relaxed">{{ $option['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section id="cta" class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-10 bg-[#500472]"></div>
            <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/10 blur-[100px]"></div>
            <div class="max-w-3xl mx-auto px-6 py-24 text-center text-white">
                <h2 class="text-4xl font-bold tracking-tight mb-4">{{ $content['cta']['heading'] }}</h2>
                <p class="text-lg text-white/80 mb-8">{{ $content['cta']['body'] }}</p>
                <a href="#" class="inline-flex items-center justify-center px-8 py-4 rounded-lg font-semibold bg-white text-[#500472] hover:bg-white/90 transition">
                    {{ $content['cta']['buttonLabel'] }}
                </a>
            </div>
        </section>

    </main>
@endsection
