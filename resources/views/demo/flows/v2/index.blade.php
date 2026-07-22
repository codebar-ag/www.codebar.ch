@extends('demo._layout')

@php($hideDemoBar = true)
@php($variantTitle = 'Swiss-Grid · 10 Illustrations-Varianten')

@section('content')
    <main class="min-h-screen bg-white text-zinc-900">
        <div class="max-w-5xl mx-auto px-6 py-20">
            <div class="text-sm font-medium text-zinc-400 tracking-widest uppercase mb-4">Flows · Swiss-Grid Familie</div>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">Eine Struktur, zehn Illustrationssprachen.</h1>
            <p class="text-lg text-zinc-500 max-w-2xl mb-6">
                Alle zehn Varianten übernehmen das Swiss-Grid-Gerüst (Label-Spalte, dünne Regeln, nummerierte Sektionen)
                und laufen im echten Seiten-Layout mit Header &amp; Footer. Der Unterschied liegt in der Illustration.
            </p>
            <a href="{{ route('demo.flows.index') }}" class="inline-block text-sm text-zinc-400 hover:text-zinc-600 underline underline-offset-4 mb-16">
                ← zurück zur ersten Runde (10 Grundlayouts)
            </a>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-zinc-200 rounded-2xl overflow-hidden border border-zinc-200">
                @foreach($variants as $key => $variant)
                    <a href="{{ route('demo.flows.v2.show', ['variant' => $key]) }}"
                       class="group bg-white hover:bg-zinc-50 transition p-8 flex flex-col gap-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="text-xs font-mono text-zinc-400">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <span class="text-xs font-mono text-zinc-300 group-hover:text-zinc-500 transition">{{ $key }}</span>
                        </div>
                        <h2 class="text-xl font-semibold group-hover:text-[#500472] transition">{{ $variant['title'] }}</h2>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $variant['description'] }}</p>
                        <span class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-zinc-700 group-hover:gap-2 transition-all">
                            Ansehen
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </span>
                    </a>
                @endforeach
            </div>

            <p class="mt-16 text-zinc-400 text-sm">
                Nur lokal sichtbar (nicht in Produktion geroutet) · <code>/demo/flows/v2</code>
            </p>
        </div>
    </main>
@endsection
