<x-app-layout :page="$page">
    <x-demo.bar :href="route('demo.flows.v2.index')" label="Alle Swiss-Grid Varianten"
                :title="$variantTitle ?? ''" :inset="true"/>

    @yield('content')
</x-app-layout>
