<x-app-layout :page="$page">
    <x-ui.hero :eyebrow="__('Journal')" :title="$title" :teaser="$teaser" />

    <x-content :content="$content" />

    <x-ui.section spacing="tight">
        <div class="mx-auto max-w-3xl space-y-8">
            @if(!empty($tags) && $tags->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <x-ui.badge :label="$tag" />
                    @endforeach
                </div>
            @endif

            <x-blocks.meta-strip :items="[
                ['label' => __('Published at'), 'value' => $published_at],
                ['label' => __('Last updated at'), 'value' => $last_updated_at],
                ['label' => __('Author'), 'value' => $author],
            ]" />
        </div>
    </x-ui.section>
</x-app-layout>
