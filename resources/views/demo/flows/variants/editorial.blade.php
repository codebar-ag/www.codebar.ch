@extends('demo._layout')

@section('content')
    <main class="bg-[#fbfaf8] text-zinc-900">
        <article class="max-w-2xl mx-auto px-6 py-24">

            <div class="text-sm font-medium text-zinc-400 tracking-widest uppercase mb-6">Flows — Produktnotiz</div>

            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">
                {{ $content['headline'] }}
            </h1>

            <p class="text-2xl text-zinc-500 leading-snug mb-16 font-light">
                {{ $content['subheadline'] }}
            </p>

            <div class="prose prose-lg prose-zinc max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-p:leading-[1.8]">

                <h2 class="!text-3xl !mt-0">{{ $content['problem']['heading'] }}</h2>

                <p class="first-letter:text-6xl first-letter:font-bold first-letter:mr-2 first-letter:float-left first-letter:leading-[0.8] first-letter:text-[#500472]">
                    {{ $content['problem']['intro'] }} {{ $content['problem']['paragraphs'][0] }}
                </p>

                <blockquote class="border-l-2 border-[#500472] pl-6 my-10 text-xl italic text-zinc-700 not-prose">
                    „Selbst mit einem Extraktionstool muss weiterhin eine Person jedes Ergebnis prüfen, bevor es vertrauenswürdig ist.“
                </blockquote>

                <p>{{ $content['problem']['paragraphs'][1] }}</p>

                <h2>{{ $content['features']['heading'] }}</h2>
                <p>{{ $content['features']['intro'] }}</p>

                <ol class="[&>li]:pl-2">
                    @foreach($content['features']['items'] as $feature)
                        <li class="mb-4"><strong>{{ $feature['title'] }}.</strong> {{ $feature['description'] }}</li>
                    @endforeach
                </ol>

                <h2>{{ $content['deployment']['heading'] }}</h2>
                <p>{{ $content['deployment']['intro'] }}</p>

                <dl class="not-prose divide-y divide-zinc-200 border-y border-zinc-200 my-8">
                    @foreach($content['deployment']['options'] as $option)
                        <div class="py-5">
                            <dt class="font-semibold text-lg mb-1">{{ $option['title'] }}</dt>
                            <dd class="text-zinc-600 leading-relaxed">{{ $option['description'] }}</dd>
                        </div>
                    @endforeach
                </dl>

            </div>

            <div class="mt-20 pt-10 border-t border-zinc-200 text-center">
                <h2 class="text-2xl font-bold mb-2">{{ $content['cta']['heading'] }}</h2>
                <p class="text-zinc-500 mb-6">{{ $content['cta']['body'] }}</p>
                <a href="#" class="text-[#500472] font-semibold underline underline-offset-4 decoration-[#500472]/30 hover:decoration-[#500472] transition">
                    {{ $content['cta']['buttonLabel'] }} →
                </a>
            </div>

        </article>
    </main>
@endsection
