<x-app-layout>

    <x-h1 :title="$title"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-section>
        <div class="prose prose-md max-w-none">
            {!! $content !!}
        </div>
    </x-section>

</x-app-layout>