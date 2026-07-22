@extends('demo._layout')

@section('content')
    <main class="bg-white text-zinc-900">

        <section class="max-w-4xl mx-auto px-6 pt-24 pb-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">{{ $content['headline'] }}</h1>
            <p class="text-xl text-zinc-500 max-w-2xl mx-auto">{{ $content['subheadline'] }}</p>
        </section>

        {{-- Before / After --}}
        <section class="max-w-5xl mx-auto px-6 pb-24">
            <h2 class="text-2xl font-bold text-center mb-2">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-500 text-center max-w-2xl mx-auto mb-12">{{ $content['problem']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-zinc-200 rounded-2xl overflow-hidden">
                <div class="bg-zinc-50 p-8">
                    <div class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-500 uppercase tracking-wider mb-4">
                        <span class="h-2 w-2 rounded-full bg-zinc-400"></span> Ohne Flows
                    </div>
                    <p class="text-zinc-600 leading-relaxed">{{ $content['problem']['paragraphs'][0] }}</p>
                </div>
                <div class="bg-white p-8">
                    <div class="inline-flex items-center gap-2 text-sm font-semibold text-[#500472] uppercase tracking-wider mb-4">
                        <span class="h-2 w-2 rounded-full bg-[#500472]"></span> Ohne Validierung
                    </div>
                    <p class="text-zinc-600 leading-relaxed">{{ $content['problem']['paragraphs'][1] }}</p>
                </div>
            </div>
        </section>

        {{-- Features as "after" reveal --}}
        <section class="bg-zinc-950 text-white">
            <div class="max-w-5xl mx-auto px-6 py-24">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 uppercase tracking-wider mb-4">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Mit Flows
                    </div>
                    <h2 class="text-3xl font-bold mb-4">{{ $content['features']['heading'] }}</h2>
                    <p class="text-white/60 max-w-2xl mx-auto">{{ $content['features']['intro'] }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($content['features']['items'] as $feature)
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">{{ $feature['title'] }}</h3>
                                <p class="text-white/60 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Deployment --}}
        <section class="max-w-5xl mx-auto px-6 py-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ $content['deployment']['heading'] }}</h2>
                <p class="text-zinc-500 max-w-2xl mx-auto">{{ $content['deployment']['intro'] }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($content['deployment']['options'] as $option)
                    <div class="text-center p-6">
                        <h3 class="font-semibold text-lg mb-2">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="bg-[#500472] text-white text-center px-6 py-20">
            <h2 class="text-3xl font-bold mb-3">{{ $content['cta']['heading'] }}</h2>
            <p class="text-white/80 mb-8 max-w-xl mx-auto">{{ $content['cta']['body'] }}</p>
            <a href="#" class="inline-flex items-center justify-center px-8 py-4 rounded-lg font-semibold bg-white text-[#500472] hover:bg-white/90 transition">
                {{ $content['cta']['buttonLabel'] }}
            </a>
        </section>

    </main>
@endsection
