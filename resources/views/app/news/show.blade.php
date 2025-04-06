<x-app-layout>

    <x-section>
        <x-badge :label="__('DMS/ECM')" class-attributes="text-xs"/>
        <x-badge :label="__('DocuWare')" class-attributes="text-xs"/>
    </x-section>

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
        <x-h2 :title="__('Meta information')"/>
        <div class="flex flex-col gap-y-2 md:flex-row md:items-center md:gap-x-2">
            <x-badge :label="__('Created at: :date', ['date' => $published_at])" :title="__('Created at')"
                     class-attributes="text-sm"/>
            <x-badge :label="__('Last updated at: :date', ['date' => $last_updated_at])"
                     :title="__('Last updated at')"
                     class-attributes="text-sm"/>
            <x-badge :label="__('Author: :name', ['name' => $author])" :title="__('Author')"
                     class-attributes="text-sm"/>
        </div>
    </x-section>

</x-app-layout>