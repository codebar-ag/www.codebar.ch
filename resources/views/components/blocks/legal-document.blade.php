@props([
    'title',
    'intro',
    'sections' => [],
])

<x-ui.hero :title="$title" :teaser="$intro" eyebrow="{{ __('Legal') }}" />

<x-ui.section>
    <div class="mx-auto max-w-3xl space-y-12">
        @foreach($sections as $section)
            <section>
                <h3 class="text-xl md:text-2xl font-semibold tracking-tight text-zinc-950">{{ $section['heading'] }}</h3>
                <div class="mt-3 prose prose-zinc max-w-none">
                    {!! $section['content'] !!}
                </div>
            </section>
        @endforeach
    </div>
</x-ui.section>
