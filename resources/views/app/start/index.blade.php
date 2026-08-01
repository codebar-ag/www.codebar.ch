<x-app-layout :page="$page">

    <x-ui.language-suggestion/>

    <x-intro/>

    <x-news.latest :articles="$latestNews"/>

    <x-explore/>

</x-app-layout>
