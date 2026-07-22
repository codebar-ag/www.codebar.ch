@extends('demo._layout')

@php
    $sections = [
        ['id' => 'ueberblick', 'label' => 'Überblick'],
        ['id' => 'problem', 'label' => 'Das Problem'],
        ['id' => 'plattform', 'label' => 'Die Plattform'],
        ['id' => 'deployment', 'label' => 'Deployment'],
        ['id' => 'kontakt', 'label' => 'Kontakt'],
    ];
@endphp

@section('content')
    <main class="bg-white text-zinc-900">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-[220px_1fr] gap-16">

            <aside class="hidden md:block">
                <div class="sticky top-16 py-16">
                    <div class="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-4">Flows</div>
                    <nav class="space-y-1">
                        @foreach($sections as $section)
                            <a href="#{{ $section['id'] }}" class="block text-sm text-zinc-600 hover:text-[#500472] py-1.5 border-l-2 border-transparent hover:border-[#500472] pl-3 -ml-px transition">
                                {{ $section['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="py-16 max-w-2xl">

                <section id="ueberblick" class="mb-20 scroll-mt-16">
                    <h1 class="text-4xl font-bold tracking-tight mb-4">{{ $content['headline'] }}</h1>
                    <p class="text-xl text-zinc-500 leading-relaxed">{{ $content['subheadline'] }}</p>
                </section>

                <section id="problem" class="mb-20 scroll-mt-16">
                    <h2 class="text-2xl font-bold mb-4">{{ $content['problem']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed mb-4 font-medium">{{ $content['problem']['intro'] }}</p>
                    @foreach($content['problem']['paragraphs'] as $p)
                        <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
                    @endforeach
                </section>

                <section id="plattform" class="mb-20 scroll-mt-16">
                    <h2 class="text-2xl font-bold mb-4">{{ $content['features']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed mb-8">{{ $content['features']['intro'] }}</p>
                    <div class="space-y-6">
                        @foreach($content['features']['items'] as $feature)
                            <div class="border-l-2 border-zinc-200 pl-5 hover:border-[#500472] transition">
                                <h3 class="font-semibold mb-1">{{ $feature['title'] }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section id="deployment" class="mb-20 scroll-mt-16">
                    <h2 class="text-2xl font-bold mb-4">{{ $content['deployment']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed mb-8">{{ $content['deployment']['intro'] }}</p>
                    <div class="space-y-6">
                        @foreach($content['deployment']['options'] as $option)
                            <div class="rounded-lg bg-zinc-50 p-5">
                                <h3 class="font-semibold mb-1">{{ $option['title'] }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section id="kontakt" class="mb-20 scroll-mt-16 rounded-xl bg-zinc-950 text-white p-8">
                    <h2 class="text-2xl font-bold mb-2">{{ $content['cta']['heading'] }}</h2>
                    <p class="text-white/70 mb-6">{{ $content['cta']['body'] }}</p>
                    <a href="#" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-white text-zinc-900 font-semibold text-sm hover:bg-white/90 transition">
                        {{ $content['cta']['buttonLabel'] }}
                    </a>
                </section>

            </div>
        </div>
    </main>
@endsection
