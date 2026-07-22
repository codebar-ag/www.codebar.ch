@php
    $sections = ['who_we_are', 'what_we_do', 'how_we_work'];
@endphp

<x-h1 :title="__('components.intro.title')" />

@foreach ($sections as $key)
    <x-layout.section>
        <x-h2 :title="__('components.intro.' . $key . '.title')"/>
        {{-- A section's text may be a single paragraph or a list of them; markup like <b> comes from our own lang files. --}}
        @foreach (\Illuminate\Support\Arr::wrap(__('components.intro.' . $key . '.text')) as $paragraph)
            <p @class(['mt-3' => ! $loop->first])>{!! $paragraph !!}</p>
        @endforeach

        @php $items = __('components.intro.' . $key . '.items'); @endphp
        @if (is_array($items))
            <ul class="mt-3 space-y-2">
                @foreach ($items as $item)
                    <li class="flex gap-3">
                        <span class="mt-2.5 size-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                        <span>{!! $item !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-layout.section>
@endforeach
