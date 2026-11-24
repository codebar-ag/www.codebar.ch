@php
    $sections = [
        ['key' => 'who_we_are', 'command' => __('components.intro.who_we_are.command'), 'next' => 1],
        ['key' => 'what_we_do', 'command' => __('components.intro.what_we_do.command'), 'next' => 2],
        ['key' => 'how_we_work', 'command' => __('components.intro.how_we_work.command'), 'next' => null],
    ];

    $cap = 'inline-flex min-w-5 items-center justify-center rounded-[3px] border border-(--it-key-line) bg-(--it-key) px-1 py-0.5 text-[0.6875rem] leading-none text-(--it-text)';
    $pill = 'inline-flex min-h-control cursor-pointer items-center gap-2 rounded-pill border px-4 py-1.5 leading-snug transition select-none max-sm:w-full sm:min-w-0 sm:max-md:px-3';
    $ghost = 'border-(--it-btn-line) text-(--it-text) hover:border-(--it-accent) hover:bg-(--it-accent) hover:text-(--it-bg)';
    $solid = 'border-(--it-accent) bg-(--it-accent) text-(--it-bg) hover:border-white hover:bg-white';
@endphp

<x-layout.section class="mt-0!">
    <x-h1 :title="__('components.intro.title')"/>

    <fieldset class="intro-tabs min-w-0 overflow-hidden rounded-panel bg-(--it-bg) font-mono text-[0.8125rem] leading-[1.7] text-(--it-text) shadow-[0_30px_60px_-30px_rgb(80_4_114/0.6)]" x-data="introTabs">
        <legend class="sr-only">{{ __('components.intro.legend') }}</legend>
        <p class="sr-only">{{ __('components.intro.shortcuts') }}</p>

        <div class="flex items-center gap-2 bg-(--it-bar) px-4 py-3">
            <span class="size-3 rounded-full bg-red-400" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-amber-400" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-emerald-400" aria-hidden="true"></span>
            <span class="ml-3 truncate text-xs text-(--it-dim)">
                {{ config('company.legal_name') }} — zsh
            </span>
        </div>

        <div class="intro-tabs__tabs flex overflow-x-auto border-y border-(--it-line) bg-(--it-bar)" data-intro-tabs>
            @foreach($sections as $section)
                <label for="intro-tab-{{ $loop->index }}"
                       class="flex shrink-0 cursor-pointer items-center gap-2.5 border-t-2 border-r border-t-transparent border-r-(--it-line) px-3 py-3 text-(--it-dim) transition select-none hover:text-white has-checked:border-t-(--it-accent) has-checked:bg-(--it-bg) has-checked:text-white has-focus-visible:outline-2 has-focus-visible:-outline-offset-2 has-focus-visible:outline-(--it-accent) sm:px-4">
                    <input type="radio" name="intro-tab" id="intro-tab-{{ $loop->index }}"
                           data-tab="{{ $loop->index }}" data-shortcut="{{ $loop->iteration }}"
                           @checked($loop->first) class="sr-only"/>
                    <kbd class="{{ $cap }} max-sm:hidden" aria-hidden="true">{{ $loop->iteration }}</kbd>
                    {{ $section['command'] }}
                </label>
            @endforeach

            <span class="ml-auto hidden shrink-0 items-center gap-1.5 px-4 sm:flex">
                <button type="button" @click="step(-1)" aria-label="{{ __('components.intro.prev_section') }}"
                        class="{{ $cap }} cursor-pointer transition hover:border-(--it-accent)">←</button>
                <button type="button" @click="step(1)" aria-label="{{ __('components.intro.next_section') }}"
                        class="{{ $cap }} cursor-pointer transition hover:border-(--it-accent)">→</button>
            </span>
        </div>

        <div class="intro-tabs__panels p-5 md:p-6 lg:p-8">
            @foreach($sections as $section)
                <div class="intro-tabs__panel min-w-0" data-panel="{{ $loop->index }}">
                    <h2 class="sr-only">{{ __('components.intro.'.$section['key'].'.title') }}</h2>

                    <div data-intro-text>
                        @foreach (\Illuminate\Support\Arr::wrap(__('components.intro.'.$section['key'].'.text')) as $paragraph)
                            <p @class(['max-w-[68ch]', 'mt-3' => ! $loop->first])>{!! $paragraph !!}</p>
                        @endforeach

                        @php($items = __('components.intro.'.$section['key'].'.items'))
                        @if (is_array($items))
                            <ul class="mt-4 space-y-2">
                                @foreach ($items as $item)
                                    <li class="flex max-w-[68ch] gap-3">
                                        <span class="text-(--it-accent)" aria-hidden="true">▸</span>
                                        <span class="[&_b]:font-bold [&_b]:text-(--it-strong)">{!! $item !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <p class="intro-tabs__actions mt-auto flex flex-wrap items-center gap-3 pt-6 sm:flex-nowrap">
                        @if($section['next'] !== null)
                            <label for="intro-tab-{{ $section['next'] }}" class="{{ $pill }} {{ $ghost }}">
                                <span aria-hidden="true">→</span>
                                {{ __('components.intro.next', ['title' => $sections[$section['next']]['command']]) }}
                            </label>
                        @else
                            <a href="{{ localized_route('services.index') }}" class="{{ $pill }} {{ $solid }}">
                                <span aria-hidden="true">→</span>
                                {{ __('components.intro.expertise') }}
                            </a>
                            <a href="{{ localized_route('contact.index') }}" class="{{ $pill }} {{ $ghost }}">
                                <span aria-hidden="true">→</span>
                                {{ __('components.intro.cta') }}
                            </a>
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
    </fieldset>
</x-layout.section>
