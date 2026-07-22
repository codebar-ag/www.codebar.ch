@extends('demo._layout')

@section('content')
    <main class="bg-black text-zinc-300 min-h-screen font-mono">
        <div class="max-w-4xl mx-auto px-6 py-20">

            {{-- Terminal window --}}
            <div class="rounded-xl border border-zinc-800 bg-zinc-950 overflow-hidden shadow-2xl shadow-black/50">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-zinc-800 bg-zinc-900/50">
                    <span class="h-3 w-3 rounded-full bg-red-500/70"></span>
                    <span class="h-3 w-3 rounded-full bg-yellow-500/70"></span>
                    <span class="h-3 w-3 rounded-full bg-green-500/70"></span>
                    <span class="ml-3 text-xs text-zinc-500">flows — zsh</span>
                </div>

                <div class="p-8 md:p-12">
                    <div class="text-emerald-400 text-sm mb-2">$ flows --about</div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white leading-snug mb-4">
                        <span class="text-[#c026d3]">&gt;</span> {{ $content['headline'] }}
                    </h1>
                    <p class="text-zinc-400 leading-relaxed mb-10 max-w-2xl">{{ $content['subheadline'] }}</p>

                    <div class="text-emerald-400 text-sm mb-2">$ flows problem --explain</div>
                    <div class="border border-zinc-800 rounded-lg p-6 mb-10 bg-black/40">
                        <div class="text-white font-semibold mb-3"># {{ $content['problem']['heading'] }}</div>
                        <p class="text-zinc-400 text-sm mb-3">// {{ $content['problem']['intro'] }}</p>
                        @foreach($content['problem']['paragraphs'] as $p)
                            <p class="text-zinc-400 text-sm leading-relaxed mb-3">{{ $p }}</p>
                        @endforeach
                    </div>

                    <div class="text-emerald-400 text-sm mb-2">$ flows features --list</div>
                    <div class="border border-zinc-800 rounded-lg p-6 mb-10 bg-black/40">
                        <div class="text-white font-semibold mb-2"># {{ $content['features']['heading'] }}</div>
                        <p class="text-zinc-500 text-sm mb-5">// {{ $content['features']['intro'] }}</p>
                        <ul class="space-y-4">
                            @foreach($content['features']['items'] as $feature)
                                <li class="flex gap-3">
                                    <span class="text-[#c026d3] flex-shrink-0">▸</span>
                                    <div>
                                        <span class="text-white font-semibold">{{ $feature['title'] }}</span>
                                        <p class="text-zinc-500 text-sm leading-relaxed mt-0.5">{{ $feature['description'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="text-emerald-400 text-sm mb-2">$ flows deploy --list-targets</div>
                    <div class="border border-zinc-800 rounded-lg p-6 mb-10 bg-black/40">
                        <div class="text-white font-semibold mb-2"># {{ $content['deployment']['heading'] }}</div>
                        <p class="text-zinc-500 text-sm mb-5">// {{ $content['deployment']['intro'] }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($content['deployment']['options'] as $i => $option)
                                <div class="border border-zinc-800 rounded-lg p-4">
                                    <div class="text-[#c026d3] text-xs mb-2">[{{ $i + 1 }}]</div>
                                    <div class="text-white font-semibold text-sm mb-2">{{ $option['title'] }}</div>
                                    <p class="text-zinc-500 text-xs leading-relaxed">{{ $option['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-emerald-400 text-sm mb-2">$ flows contact --start</div>
                    <div class="flex items-center justify-between border border-emerald-900/50 bg-emerald-950/20 rounded-lg p-6">
                        <div>
                            <div class="text-white font-semibold">{{ $content['cta']['heading'] }}</div>
                            <p class="text-zinc-400 text-sm">{{ $content['cta']['body'] }}</p>
                        </div>
                        <a href="#" class="flex-shrink-0 px-5 py-2.5 rounded-md bg-emerald-500 text-black font-semibold text-sm hover:bg-emerald-400 transition">
                            {{ $content['cta']['buttonLabel'] }}
                        </a>
                    </div>

                    <div class="text-emerald-400 text-sm mt-6">$ <span class="animate-pulse">▌</span></div>
                </div>
            </div>

        </div>
    </main>
@endsection
