<x-app-layout :page="$page">
    <x-h1 :title="__('About us')"/>
    <x-section>
        Lorem Ipsums Lorem Ipsum Lorem Ipsum
    </x-section>

    <x-section>
        <x-h3 :title="__('paperflakes')"/>

        <x-h2 :title="__('Services')"/>
        <ul>
            <li>Mischa Lanz Bürgin-Fix</li>
            <li>Sebastian Bürgin-Fix</li>
        </ul>

        <x-h3 :title="__('Products')"/>
        <ul>
            <li>Sebastian Bürgin-Fix</li>
            <li>Rhys Lees</li>
        </ul>

    </x-section>

    <x-section>
        <x-h2 :title="__('Collaborations')"/>
        <ul>
            <li>Dario Wieland - [Wieland Business Solutions GmbH] - [DMS/ECM]</li>
            <li>Matthias Friedli - [Friedli Projektmanagement GmbH] - [DMS/ECM]</li>
        </ul>
    </x-section>

    <x-section>
        <x-h2 :title="__('Board of directors')"/>
        <ul>
            <li>Dominique Ernst</li>
            <li>Mischa Lanz</li>
            <li>Sebastian Bürgin-Fix</li>
        </ul>
    </x-section>

</x-app-layout>