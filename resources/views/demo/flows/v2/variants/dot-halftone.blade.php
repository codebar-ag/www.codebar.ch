@extends('demo._app_layout')

@php
    $cols = 18;
    $rows = 7;
    $spacing = 14;
    $cx = ($cols - 1) * $spacing / 2;
    $cy = ($rows - 1) * $spacing / 2;
    $maxDist = sqrt($cx ** 2 + $cy ** 2);
    $dots = '';
    for ($row = 0; $row < $rows; $row++) {
        for ($col = 0; $col < $cols; $col++) {
            $x = $col * $spacing;
            $y = $row * $spacing;
            $dist = sqrt(($x - $cx) ** 2 + ($y - $cy) ** 2);
            $radius = max(0.6, 5.2 * (1 - $dist / $maxDist));
            $dots .= "<circle cx=\"{$x}\" cy=\"{$y}\" r=\"{$radius}\" fill=\"#500472\"/>";
        }
    }
    $width = ($cols - 1) * $spacing;
    $height = ($rows - 1) * $spacing;
@endphp

@section('content')

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">Flows</div>
        <div class="col-span-12 md:col-span-10">
            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full max-w-md h-auto mb-8" xmlns="http://www.w3.org/2000/svg">
                {!! $dots !!}
            </svg>
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-[1.1] mb-6">{{ $content['headline'] }}</h1>
            <p class="text-lg text-zinc-500 max-w-xl">{{ $content['subheadline'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">01 — Problem</div>
        <div class="col-span-12 md:col-span-7">
            <h2 class="text-2xl font-bold mb-6 leading-snug">{{ $content['problem']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed mb-4">{{ $content['problem']['intro'] }}</p>
            @foreach($content['problem']['paragraphs'] as $p)
                <p class="text-zinc-600 leading-relaxed mb-4">{{ $p }}</p>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">02 — Plattform</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['features']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['features']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                @foreach($content['features']['items'] as $i => $feature)
                    <div class="border-t border-zinc-200 pt-4 flex gap-4">
                        <svg viewBox="0 0 30 30" class="w-6 h-6 flex-shrink-0 mt-1">
                            <circle cx="5" cy="5" r="4.5" fill="#500472"/>
                            <circle cx="17" cy="5" r="3" fill="#500472" opacity="0.6"/>
                            <circle cx="26" cy="5" r="1.5" fill="#500472" opacity="0.3"/>
                            <circle cx="5" cy="17" r="3" fill="#500472" opacity="0.6"/>
                            <circle cx="17" cy="17" r="4.5" fill="#500472"/>
                            <circle cx="5" cy="26" r="1.5" fill="#500472" opacity="0.3"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold mb-1.5">{{ $feature['title'] }}</h3>
                            <p class="text-zinc-500 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-zinc-900 pt-4 mb-16">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">03 — Deployment</div>
        <div class="col-span-12 md:col-span-10">
            <h2 class="text-2xl font-bold mb-4 leading-snug max-w-2xl">{{ $content['deployment']['heading'] }}</h2>
            <p class="text-zinc-600 leading-relaxed max-w-2xl mb-12">{{ $content['deployment']['intro'] }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-8">
                @foreach($content['deployment']['options'] as $option)
                    <div class="border-t border-zinc-200 pt-4">
                        <h3 class="font-semibold mb-1.5">{{ $option['title'] }}</h3>
                        <p class="text-zinc-500 text-sm leading-relaxed">{{ $option['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6 border-t border-b border-zinc-900 py-10">
        <div class="col-span-12 md:col-span-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">04 — Kontakt</div>
        <div class="col-span-12 md:col-span-10 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $content['cta']['heading'] }}</h2>
                <p class="text-zinc-500 max-w-md">{{ $content['cta']['body'] }}</p>
            </div>
            <a href="#" class="flex-shrink-0 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-widest border-b border-zinc-900 pb-1">
                {{ $content['cta']['buttonLabel'] }} →
            </a>
        </div>
    </div>

@endsection
