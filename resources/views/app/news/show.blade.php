<x-app-layout :page="$page">

    @if(!empty($tags) && $tags->count())
        <x-section>
            @foreach($tags as $tag)
                <x-badge :label="$tag" class-attributes="text-xs"/>
            @endforeach
        </x-section>
    @endif


    <x-h1 :title="$title"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-section>
        <x-content :content="$content"/>

        <div class="mt-8">
            <h2 class="mb-2 text-2xl font-semibold">{{ __('Meta information') }}</h2>
            <div class="mt-6 flex flex-col gap-y-2 md:flex-row md:items-center md:gap-x-2">
                <x-badge :label="__('Published at: :date', ['date' => $published_at])"
                         :title="__('Published at')"
                         class-attributes="text-sm self-start"/>
                <x-badge :label="__('Last updated at: :date', ['date' => $last_updated_at])"
                         :title="__('Last updated at')"
                         class-attributes="text-sm self-start"/>
                <x-badge :label="__('Author: :name', ['name' => $author])"
                         :title="__('Author')"
                         class-attributes="text-sm self-start"/>
            </div>
        </div>
    </x-section>
</x-app-layout>