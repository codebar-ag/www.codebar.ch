<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Media')" :intro="__('Media intro')"/>

    <x-layout.section>
        <x-h2 :title="__('Logos')"/>

        <x-layout.grid :cols="2" gap="gap-6">
            @foreach($logos as $logo)
                <x-card.download-card
                        :label="$logo['label']"
                        :image="asset('images/logos/' . $logo['slug'] . '.png')"
                        :inverted="$logo['slug'] === 'codebar-logo-colored-inverted'"
                        :links="[
                            ['href' => asset('images/logos/' . $logo['slug'] . '.png'), 'label' => '.png', 'download' => $logo['slug'] . '.png'],
                            ['href' => asset('images/logos/' . $logo['slug'] . '.svg'), 'label' => '.svg', 'download' => $logo['slug'] . '.svg'],
                        ]"/>
            @endforeach
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Media usage')"/>
        <x-ui.prose>
            <p>{{ __('Media usage allowed') }}</p>
            <p>{{ __('Media usage forbidden') }}</p>
        </x-ui.prose>
    </x-layout.section>
</x-app-layout>
