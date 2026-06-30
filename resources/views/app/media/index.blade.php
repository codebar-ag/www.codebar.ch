<x-app-layout :page="$page">
    <x-h1 :title="__('Media')"/>

    <x-section>
        <p>{{ __('Media intro') }}</p>
    </x-section>

    <x-section>
        <x-h2 :title="__('Logos')"/>

        <x-list-grid class-attributes="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($logos as $logo)
                <div class="flex flex-col gap-4 rounded-xl border border-gray-200 p-6">
                    <div @class([
                        'flex min-h-[80px] items-center justify-center rounded-lg p-4',
                        'bg-zinc-950' => $logo['slug'] === 'codebar-logo-colored-inverted',
                        'bg-white' => $logo['slug'] !== 'codebar-logo-colored-inverted',
                    ])>
                        <img
                            src="{{ asset('images/logos/' . $logo['slug'] . '.png') }}"
                            alt="{{ $logo['label'] }}"
                            class="max-h-16 w-auto"
                        />
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="font-semibold text-gray-800">{{ $logo['label'] }}</span>
                        <div class="flex gap-4">
                            <x-a
                                :href="asset('images/logos/' . $logo['slug'] . '.png')"
                                label=".png"
                                :download="$logo['slug'] . '.png'"
                                classAttributes="text-base"
                            />
                            <x-a
                                :href="asset('images/logos/' . $logo['slug'] . '.svg')"
                                label=".svg"
                                :download="$logo['slug'] . '.svg'"
                                classAttributes="text-base"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </x-list-grid>
    </x-section>
</x-app-layout>
