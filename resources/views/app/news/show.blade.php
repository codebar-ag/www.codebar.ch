<x-app-layout :page="$page">

    @if(!empty($tags) && $tags->count())
        <x-layout.section>
            <x-data.tag-list :tags="$tags"/>
        </x-layout.section>
    @endif

    <x-h1 :title="$title"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>

        <div class="mt-8">
            <x-h2 :title="__('Meta information')"/>
            <x-data.meta-badges class="mt-6" :items="[
                __('Published at') => __('Published at: :date', ['date' => $published_at]),
                __('Last updated at') => __('Last updated at: :date', ['date' => $last_updated_at]),
                __('Author') => __('Author: :name', ['name' => $author]),
            ]"/>
        </div>
    </x-layout.section>
</x-app-layout>
