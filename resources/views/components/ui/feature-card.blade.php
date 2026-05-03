@props([
    'title',
    'teaser' => null,
    'classAttributes' => '',
])

<x-ui.card class-attributes="{{ $classAttributes }}">
    <x-ui.stack gap="3">
        <x-ui.headline :title="$title" level="h3" class-attributes="text-gray-900" />
        @if(filled($teaser))
            <x-ui.text>{{ $teaser }}</x-ui.text>
        @endif
        {{ $slot }}
    </x-ui.stack>
</x-ui.card>
