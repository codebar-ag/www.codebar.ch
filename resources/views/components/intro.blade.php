@php
    $sections = [
        ['key' => 'start', 'command' => __('components.intro.start_command'), 'next' => 1],
        ['key' => 'who_we_are', 'command' => __('components.intro.who_we_are.command'), 'next' => 2],
        ['key' => 'what_we_do', 'command' => __('components.intro.what_we_do.command'), 'next' => 3],
        ['key' => 'how_we_work', 'command' => __('components.intro.how_we_work.command'), 'next' => null],
    ];

    $cap = 'inline-flex min-w-5 items-center justify-center rounded-[3px] border border-zinc-700 bg-zinc-800 px-1 py-0.5 font-mono text-[0.6875rem] leading-none text-zinc-300';
    $pill = 'inline-flex min-h-control cursor-pointer items-center gap-2 rounded-pill px-4 text-sm transition select-none';
@endphp

<x-layout.section class="mt-0!">
    <fieldset class="intro-tabs min-w-0 overflow-hidden rounded-panel border border-zinc-800 bg-zinc-950" x-data="introTabs">
        <legend class="sr-only">{{ __('components.intro.legend') }}</legend>
        <p class="sr-only">{{ __('components.intro.shortcuts') }}</p>

        <div class="flex items-center gap-2 border-b border-zinc-800 px-4 py-3">
            <span class="size-3 rounded-full bg-red-500/80" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-amber-400/80" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-emerald-500/80" aria-hidden="true"></span>
            <span class="ml-3 truncate font-mono text-xs text-zinc-500">{{ config('company.legal_name') }}</span>
        </div>

        <div class="flex flex-col border-b border-zinc-800 bg-zinc-900/60 sm:flex-row sm:overflow-x-auto">
            @foreach($sections as $section)
                <label for="intro-tab-{{ $loop->index }}"
                       class="flex shrink-0 cursor-pointer items-center gap-2.5 border-b border-l-2 border-b-zinc-800 border-l-transparent px-4 py-3 font-mono text-sm text-zinc-400 transition select-none last:border-b-0 hover:text-zinc-100 has-checked:border-l-emerald-400 has-checked:bg-zinc-950 has-checked:text-white has-focus-visible:outline-2 has-focus-visible:-outline-offset-2 has-focus-visible:outline-emerald-400 sm:border-t-2 sm:border-r sm:border-b-0 sm:border-l-0 sm:border-t-transparent sm:border-r-zinc-800 sm:has-checked:border-t-emerald-400">
                    <input type="radio" name="intro-tab" id="intro-tab-{{ $loop->index }}"
                           data-tab="{{ $loop->index }}" data-shortcut="{{ $loop->iteration }}"
                           @checked($loop->first) class="sr-only"/>
                    <kbd class="{{ $cap }}" aria-hidden="true">{{ $loop->iteration }}</kbd>
                    {{ $section['command'] }}
                </label>
            @endforeach

            <span class="ml-auto hidden shrink-0 items-center gap-1.5 px-4 sm:flex" aria-hidden="true">
                <kbd class="{{ $cap }}">←</kbd>
                <kbd class="{{ $cap }}">→</kbd>
            </span>
        </div>

        <div class="intro-tabs__panels p-5 font-mono text-sm leading-relaxed sm:p-8">
            @foreach($sections as $section)
                <div class="intro-tabs__panel min-w-0" data-panel="{{ $loop->index }}">
                    <p>
                        <span class="text-emerald-400" aria-hidden="true">➜</span>
                        <span class="text-emerald-400">{{ __('components.intro.user') }}</span><span class="text-sky-400">@codebar</span>
                        <span class="text-white">{{ $section['command'] }}</span>
                    </p>

                    <div class="mt-4 pl-4 sm:pl-6">
                        @if($section['key'] === 'start')
                            @php($title = __('components.intro.title'))
                            <h1 class="text-base font-medium text-white">{{ $title }}</h1>
                            <p class="hidden text-base leading-none text-zinc-700 sm:block" aria-hidden="true">{{ str_repeat('=', mb_strlen($title)) }}</p>

                            <ul class="mt-5 space-y-2">
                                @foreach(array_slice($sections, 1) as $target)
                                    <li>
                                        <label for="intro-tab-{{ $loop->iteration }}"
                                               class="flex max-w-[68ch] cursor-pointer gap-3 select-none">
                                            <span class="text-emerald-400" aria-hidden="true">·</span>
                                            <span class="min-w-0">
                                                <span class="block text-fuchsia-400 underline-offset-4 hover:underline">{{ $target['command'] }}</span>
                                                <span class="mt-0.5 block text-zinc-400">{{ __('components.intro.'.$target['key'].'.teaser') }}</span>
                                            </span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h2 class="sr-only">{{ __('components.intro.'.$section['key'].'.title') }}</h2>

                            @foreach (\Illuminate\Support\Arr::wrap(__('components.intro.'.$section['key'].'.text')) as $paragraph)
                                <p @class(['max-w-[68ch] text-zinc-300', 'mt-3' => ! $loop->first])>{!! $paragraph !!}</p>
                            @endforeach

                            @php($items = __('components.intro.'.$section['key'].'.items'))
                            @if (is_array($items))
                                <ul class="mt-4 space-y-2">
                                    @foreach ($items as $item)
                                        <li class="flex max-w-[68ch] gap-3">
                                            <span class="text-emerald-400" aria-hidden="true">·</span>
                                            <span class="text-zinc-300 [&_b]:font-normal [&_b]:text-fuchsia-400">{!! $item !!}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        @unless($section['key'] === 'start')
                            <p class="mt-6">
                                @if($section['next'] !== null)
                                    <label for="intro-tab-{{ $section['next'] }}"
                                           class="{{ $pill }} bg-white/10 text-white hover:bg-white/20">
                                        <span aria-hidden="true">→</span>
                                        {{ __('components.intro.next', ['title' => $sections[$section['next']]['command']]) }}
                                    </label>
                                @else
                                    <a href="{{ localized_route('contact.index') }}"
                                       class="{{ $pill }} bg-emerald-500/20 text-emerald-200 hover:bg-emerald-500/30 focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400">
                                        <span aria-hidden="true">→</span>
                                        {{ __('components.intro.cta') }}
                                    </a>
                                @endif
                            </p>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>

        <p class="flex items-center gap-2 px-5 pb-5 font-mono text-sm text-zinc-500 sm:px-8 sm:pb-6" aria-hidden="true">
            <span class="text-emerald-400">➜</span>
            <span>{{ __('components.intro.user') }}@codebar</span>
            <span class="inline-block h-3.5 w-1.5 animate-pulse bg-zinc-500"></span>
        </p>
    </fieldset>
</x-layout.section>
