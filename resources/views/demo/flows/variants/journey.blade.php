@extends('demo._layout')

@section('content')
    <main class="bg-white text-zinc-900">
        <div class="max-w-3xl mx-auto px-6 py-20">

            <div class="text-center mb-24">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">{{ $content['headline'] }}</h1>
                <p class="text-xl text-zinc-500 max-w-xl mx-auto">{{ $content['subheadline'] }}</p>
            </div>

            <div class="relative">
                <div class="absolute left-6 top-2 bottom-2 w-px bg-zinc-200 hidden md:block"></div>

                {{-- Step 1: Problem --}}
                <div class="relative flex gap-8 pb-16">
                    <div class="hidden md:flex flex-shrink-0 h-12 w-12 rounded-full bg-zinc-950 text-white items-center justify-center font-bold z-10">01</div>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Das Problem</span>
                        <h2 class="text-2xl font-bold mt-2 mb-4">{{ $content['problem']['heading'] }}</h2>
                        <p class="text-zinc-600 leading-relaxed mb-3">{{ $content['problem']['intro'] }}</p>
                        @foreach($content['problem']['paragraphs'] as $p)
                            <p class="text-zinc-600 leading-relaxed mb-3">{{ $p }}</p>
                        @endforeach
                    </div>
                </div>

                {{-- Step 2: Platform intro --}}
                <div class="relative flex gap-8 pb-8">
                    <div class="hidden md:flex flex-shrink-0 h-12 w-12 rounded-full bg-zinc-950 text-white items-center justify-center font-bold z-10">02</div>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Die Plattform</span>
                        <h2 class="text-2xl font-bold mt-2 mb-4">{{ $content['features']['heading'] }}</h2>
                        <p class="text-zinc-600 leading-relaxed">{{ $content['features']['intro'] }}</p>
                    </div>
                </div>

                {{-- Step 2.x: sub-steps for each feature --}}
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="relative flex gap-8 pb-10">
                        <div class="hidden md:flex flex-shrink-0 h-12 w-12 rounded-full bg-white border-2 border-zinc-200 text-zinc-400 items-center justify-center font-semibold text-sm z-10">2.{{ $i + 1 }}</div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg mb-1.5">{{ $feature['title'] }}</h3>
                            <p class="text-zinc-500 leading-relaxed">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach

                {{-- Step 3: Deployment --}}
                <div class="relative flex gap-8 pb-16">
                    <div class="hidden md:flex flex-shrink-0 h-12 w-12 rounded-full bg-zinc-950 text-white items-center justify-center font-bold z-10">03</div>
                    <div class="flex-1">
                        <span class="text-sm font-semibold text-[#500472] uppercase tracking-wider">Deployment</span>
                        <h2 class="text-2xl font-bold mt-2 mb-4">{{ $content['deployment']['heading'] }}</h2>
                        <p class="text-zinc-600 leading-relaxed mb-6">{{ $content['deployment']['intro'] }}</p>
                        <div class="space-y-4">
                            @foreach($content['deployment']['options'] as $i => $option)
                                <div class="flex gap-3">
                                    <span class="font-mono text-sm text-zinc-400 mt-0.5">3.{{ $i + 1 }}</span>
                                    <div>
                                        <h3 class="font-semibold">{{ $option['title'] }}</h3>
                                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Step 4: CTA --}}
                <div class="relative flex gap-8">
                    <div class="hidden md:flex flex-shrink-0 h-12 w-12 rounded-full bg-[#500472] text-white items-center justify-center font-bold z-10">04</div>
                    <div class="flex-1 rounded-xl bg-zinc-50 p-8">
                        <h2 class="text-2xl font-bold mb-2">{{ $content['cta']['heading'] }}</h2>
                        <p class="text-zinc-600 mb-6">{{ $content['cta']['body'] }}</p>
                        <a href="#" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[#500472] text-white font-semibold text-sm hover:bg-[#3a0354] transition">
                            {{ $content['cta']['buttonLabel'] }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
