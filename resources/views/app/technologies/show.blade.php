<x-app-layout :page="$page">

    <x-h1 :title="$name"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>
    </x-layout.section>

</x-app-layout>
