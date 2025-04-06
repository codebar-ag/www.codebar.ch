<x-app-layout>

    @if(!empty($tags) && $tags->count())
        <x-section>
            @foreach($tags as $tag)
                <x-badge :label="$tag" class-attributes="text-xs"/>
            @endforeach
        </x-section>
    @endif


    <x-section>
        <x-h1 :title="$title"/>
        <x-h1-teaser :teaser="$teaser"/>
    </x-section>

    <x-section>
        <div class="prose prose-md max-w-none">
            {!! $content !!}
        </div>
    </x-section>

    <x-section class-attributes="mt-8">
        <h2 class="mb-2 text-2xl font-semibold">{{ __('Meta information') }}</h2>
        <div class="mt-6 flex flex-col gap-y-2 md:flex-row md:items-center md:gap-x-2">
            <x-badge :label="__('Created at: :date', ['date' => $published_at])"
                     :title="__('Created at')"
                     class-attributes="text-sm self-start"/>
            <x-badge :label="__('Last updated at: :date', ['date' => $last_updated_at])"
                     :title="__('Last updated at')"
                     class-attributes="text-sm self-start"/>
            <x-badge :label="__('Author: :name', ['name' => $author])"
                     :title="__('Author')"
                     class-attributes="text-sm self-start"/>
        </div>
    </x-section>

</x-app-layout>