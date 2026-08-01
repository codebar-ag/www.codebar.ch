@php
    $sections = [
        ['key' => 'start', 'command' => null, 'next' => null],
        ['key' => 'who_we_are', 'command' => __('components.intro.who_we_are.command'), 'next' => 2],
        ['key' => 'what_we_do', 'command' => __('components.intro.what_we_do.command'), 'next' => 3],
        ['key' => 'how_we_work', 'command' => __('components.intro.how_we_work.command'), 'next' => null],
    ];

    $cap = 'inline-flex min-w-5 items-center justify-center rounded-[3px] border border-gray-400 bg-gray-50 px-1 py-0.5 font-mono text-[0.6875rem] leading-none text-gray-700';
    $pill = 'inline-flex min-h-control cursor-pointer items-center gap-2 rounded-pill px-4 text-sm font-medium transition select-none';
@endphp

<x-layout.section class="mt-0!">
    <x-h1 :title="__('components.intro.title')"/>

    <fieldset class="intro-tabs min-w-0 overflow-hidden rounded-panel border border-gray-300 bg-gray-100" x-data="introTabs">
        <legend class="sr-only">{{ __('components.intro.legend') }}</legend>
        <p class="sr-only">{{ __('components.intro.shortcuts') }}</p>

        <div class="flex items-center gap-2 border-b border-gray-300 bg-gray-200 px-4 py-3">
            <span class="size-3 rounded-full bg-red-400" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-amber-400" aria-hidden="true"></span>
            <span class="size-3 rounded-full bg-emerald-400" aria-hidden="true"></span>
            <span class="ml-3 truncate font-mono text-xs text-gray-700">
                {{ __('components.intro.window') }} <span class="text-gray-500">–</span> {{ config('company.legal_name') }}
            </span>
        </div>

        <input type="radio" name="intro-tab" id="intro-tab-0" data-tab="0" checked class="sr-only"/>

        <div class="flex flex-col border-b border-gray-300 bg-gray-200 sm:flex-row sm:overflow-x-auto">
            @foreach(array_slice($sections, 1) as $section)
                <label for="intro-tab-{{ $loop->iteration }}"
                       class="flex shrink-0 cursor-pointer items-center gap-2.5 border-b border-l-2 border-b-gray-300 border-l-transparent px-4 py-3 font-mono text-sm text-gray-600 transition select-none last:border-b-0 hover:text-gray-900 has-checked:border-l-brand has-checked:bg-gray-100 has-checked:text-gray-900 has-focus-visible:outline-2 has-focus-visible:-outline-offset-2 has-focus-visible:outline-brand sm:border-t-2 sm:border-r sm:border-b-0 sm:border-l-0 sm:border-t-transparent sm:border-r-gray-300 sm:has-checked:border-t-brand">
                    <input type="radio" name="intro-tab" id="intro-tab-{{ $loop->iteration }}"
                           data-tab="{{ $loop->iteration }}" data-shortcut="{{ $loop->iteration }}"
                           class="sr-only"/>
                    <kbd class="{{ $cap }}" aria-hidden="true">{{ $loop->iteration }}</kbd>
                    {{ $section['command'] }}
                </label>
            @endforeach

            <span class="ml-auto hidden shrink-0 items-center gap-1.5 px-4 sm:flex" aria-hidden="true">
                <kbd class="{{ $cap }}">←</kbd>
                <kbd class="{{ $cap }}">→</kbd>
            </span>
        </div>

        <div class="intro-tabs__panels p-5 font-mono text-sm leading-relaxed text-gray-800 sm:p-8">
            @foreach($sections as $section)
                <div class="intro-tabs__panel min-w-0" data-panel="{{ $loop->index }}">
                    <div>
                        @if($section['key'] === 'start')
                            <ul class="space-y-2">
                                @foreach(array_slice($sections, 1) as $target)
                                    <li>
                                        <label for="intro-tab-{{ $loop->iteration }}"
                                               class="flex max-w-[68ch] cursor-pointer items-start gap-3 select-none">
                                            <kbd class="{{ $cap }} mt-0.5" aria-hidden="true">{{ $loop->iteration }}</kbd>
                                            <span class="text-brand underline-offset-4 hover:underline">
                                                {{ __('components.intro.'.$target['key'].'.teaser') }}
                                            </span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h2 class="sr-only">{{ __('components.intro.'.$section['key'].'.title') }}</h2>

                            @foreach (\Illuminate\Support\Arr::wrap(__('components.intro.'.$section['key'].'.text')) as $paragraph)
                                <p @class(['max-w-[68ch]', 'mt-3' => ! $loop->first])>{!! $paragraph !!}</p>
                            @endforeach

                            @php($items = __('components.intro.'.$section['key'].'.items'))
                            @if (is_array($items))
                                <ul class="mt-4 space-y-2">
                                    @foreach ($items as $item)
                                        <li class="flex max-w-[68ch] gap-3">
                                            <span class="text-gray-500" aria-hidden="true">·</span>
                                            <span class="[&_b]:font-normal [&_b]:text-brand">{!! $item !!}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        @unless($section['key'] === 'start')
                            <p class="mt-6">
                                @if($section['next'] !== null)
                                    <label for="intro-tab-{{ $section['next'] }}"
                                           class="{{ $pill }} border border-brand bg-white text-brand hover:bg-brand hover:text-white">
                                        <span aria-hidden="true">→</span>
                                        {{ __('components.intro.next', ['title' => $sections[$section['next']]['command']]) }}
                                    </label>
                                @else
                                    <a href="{{ localized_route('contact.index') }}"
                                       class="{{ $pill }} focus-ring bg-brand text-white hover:bg-brand-strong">
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
    </fieldset>
</x-layout.section>
