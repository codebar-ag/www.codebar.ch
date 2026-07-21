@extends('demo._layout')

@php($hideDemoBar = true)
@php($variantTitle = '10 Layout-Konzepte')

@section('content')
    <main class="min-h-screen bg-zinc-950 text-white">
        <div class="max-w-5xl mx-auto px-6 py-20">
            <div class="text-sm font-medium text-white/50 tracking-widest uppercase mb-4">Flows · Layout-Exploration</div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">10 Vorschläge, eine Story.</h1>
            <p class="text-lg text-white/70 max-w-2xl mb-16">
                Zehn strukturell und visuell unterschiedliche Layouts für dieselbe deutsche Flows-Content.
                Klick dich durch, notier dir was funktioniert — daraus bauen wir die finalen statischen Seiten.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-white/10 rounded-2xl overflow-hidden">
                @foreach($variants as $key => $variant)
                    <a href="{{ route('demo.flows.show', ['variant' => $key]) }}"
                       class="group bg-zinc-950 hover:bg-white/5 transition p-8 flex flex-col gap-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="text-xs font-mono text-white/40">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <span class="text-xs font-mono text-white/30 group-hover:text-white/60 transition">{{ $key }}</span>
                        </div>
                        <h2 class="text-xl font-semibold group-hover:text-white transition">{{ $variant['title'] }}</h2>
                        <p class="text-white/60 text-sm leading-relaxed">{{ $variant['description'] }}</p>
                        <span class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-white/80 group-hover:gap-2 transition-all">
                            Ansehen
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>

            <p class="mt-16 text-white/30 text-sm">
                Nur lokal sichtbar (nicht in Produktion geroutet) · <code>/demo/flows</code>
            </p>
        </div>
    </main>
@endsection
