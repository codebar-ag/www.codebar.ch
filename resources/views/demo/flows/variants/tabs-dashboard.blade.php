@extends('demo._layout')

@section('content')
    <main class="bg-zinc-50 text-zinc-900 min-h-screen">

        <section class="max-w-4xl mx-auto px-6 pt-20 pb-14 text-center">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">{{ $content['headline'] }}</h1>
            <p class="text-xl text-zinc-500 max-w-2xl mx-auto">{{ $content['subheadline'] }}</p>
        </section>

        <section class="max-w-5xl mx-auto px-6 pb-24" x-data="tabs">

            <div class="flex flex-wrap justify-center gap-2 mb-10">
                @php
                    $tabs = [
                        ['label' => 'Das Problem'],
                        ['label' => 'Die Plattform'],
                        ['label' => 'Deployment'],
                        ['label' => 'Kontakt'],
                    ];
                @endphp
                @foreach($tabs as $i => $tab)
                    <button
                        type="button"
                        @click="select({{ $i }})"
                        :class="isActive({{ $i }}) ? 'bg-zinc-950 text-white' : 'bg-white text-zinc-600 hover:bg-zinc-100'"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition border border-zinc-200"
                    >
                        {{ sprintf('%02d', $i + 1) }} {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 p-8 md:p-12 min-h-[420px]">

                <div x-show="isActive(0)">
                    <h2 class="text-2xl font-bold mb-6">{{ $content['problem']['heading'] }}</h2>
                    <p class="text-zinc-600 leading-relaxed mb-4 font-medium">{{ $content['problem']['intro'] }}</p>
                    @foreach($content['problem']['paragraphs'] as $p)
                        <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
                    @endforeach
                </div>

                <div x-show="isActive(1)">
                    <h2 class="text-2xl font-bold mb-2">{{ $content['features']['heading'] }}</h2>
                    <p class="text-zinc-500 leading-relaxed mb-8">{{ $content['features']['intro'] }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        @foreach($content['features']['items'] as $feature)
                            <div>
                                <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="isActive(2)">
                    <h2 class="text-2xl font-bold mb-2">{{ $content['deployment']['heading'] }}</h2>
                    <p class="text-zinc-500 leading-relaxed mb-8">{{ $content['deployment']['intro'] }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($content['deployment']['options'] as $option)
                            <div class="rounded-xl bg-zinc-50 p-5">
                                <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                                <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="isActive(3)" class="flex flex-col items-center text-center py-10">
                    <h2 class="text-3xl font-bold mb-3">{{ $content['cta']['heading'] }}</h2>
                    <p class="text-zinc-500 mb-8 max-w-md">{{ $content['cta']['body'] }}</p>
                    <a href="#" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold bg-[#500472] text-white hover:bg-[#3a0354] transition">
                        {{ $content['cta']['buttonLabel'] }}
                    </a>
                </div>

            </div>
        </section>

    </main>
@endsection
