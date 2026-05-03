@props([
    'title',
    'intro',
    'sections' => [],
])

<x-blocks.editorial-hero :title="$title" :teaser="$intro" eyebrow="{{ __('Legal') }}" tone="muted" />

<x-ui.section>
    <x-blocks.content-river>
        @foreach($sections as $section)
            <x-ui.card class-attributes="mb-4">
                <x-ui.headline :title="$section['heading']" level="h3" />
                <x-ui.text class-attributes="mt-2">
                    {{ $section['content'] }}
                </x-ui.text>
            </x-ui.card>
        @endforeach
    </x-blocks.content-river>
</x-ui.section>
