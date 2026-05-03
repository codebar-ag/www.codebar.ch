@props([
    'title',
    'teaser',
    'classAttributes' => '',
])

<section {{ $attributes->merge(['class' => "surface-card hero-gradient p-6 md:p-10 {$classAttributes}"]) }}>
    <x-ui.stack gap="4">
        <x-ui.headline :title="$title" level="h2" class-attributes="text-gray-900" />
        <x-ui.text size="lg" class-attributes="max-w-3xl">{{ $teaser }}</x-ui.text>
        <div class="flex flex-wrap gap-3">
            {{ $slot }}
        </div>
    </x-ui.stack>
</section>
