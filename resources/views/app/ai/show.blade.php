<x-app-layout :page="$page">

    <x-h1 :title="$service['name']"/>
    <x-h1-teaser :teaser="$service['teaser']"/>

    <x-section>
        <ul class="list-disc list-inside space-y-2 text-gray-700">
            @foreach($service['features'] as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>
    </x-section>

    @if($service['audience'])
        <x-section>
            <p class="text-gray-600 italic">{{ $service['audience'] }}</p>
        </x-section>
    @endif

    @if($service['closing'])
        <x-section>
            <p class="font-semibold text-gray-800">{{ $service['closing'] }}</p>
        </x-section>
    @endif

</x-app-layout>
