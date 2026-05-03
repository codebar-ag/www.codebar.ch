<x-app-layout :page="$page">

    <x-blocks.editorial-hero :title="$title" :teaser="$teaser" :eyebrow="__('Journal')" />

    <x-ui.section>
        <x-blocks.content-river>
            <x-content :content="$content"/>

            @if(!empty($tags) && $tags->count())
                <x-ui.section spacing="tight">
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <x-badge :label="$tag" class-attributes="text-xs"/>
                        @endforeach
                    </div>
                </x-ui.section>
            @endif

            <x-blocks.meta-strip :items="[
                ['label' => __('Published at'), 'value' => $published_at],
                ['label' => __('Last updated at'), 'value' => $last_updated_at],
                ['label' => __('Author'), 'value' => $author],
            ]"/>
        </x-blocks.content-river>
    </x-ui.section>
</x-app-layout>