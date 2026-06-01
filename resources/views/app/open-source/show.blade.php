<x-app-layout :page="$page">
    <x-ui.hero :eyebrow="__('Open Source')" :title="$name" :teaser="$teaser" />

    <x-content :content="$content" />
</x-app-layout>
