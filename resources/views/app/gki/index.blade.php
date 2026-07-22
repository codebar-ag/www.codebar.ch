<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Generative AI Consulting')"
                           :intro="__('From strategy to working prototype to productive integration — we support companies in putting generative AI to work.')"
                           :page="$page"/>

    <x-layout.section>
        <x-ui.prose>
            <p>{{ __('Our three GKI offerings support companies step by step: from strategic assessment through a working prototype in a matter of days to technical integration into existing systems.') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-layout.grid :cols="3" gap="gap-6">
            @foreach($tiers as $tier)
                <x-ui.panel class="flex flex-col px-6 py-6">
                    <div class="text-lg font-semibold text-gray-800">{{ $tier['name'] }}</div>
                    <p class="mt-1 text-muted">{{ $tier['teaser'] }}</p>

                    <ul class="mt-4 space-y-2 text-sm text-gray-800">
                        @foreach($tier['features'] as $feature)
                            <li class="flex gap-2">
                                <span class="text-brand" aria-hidden="true">—</span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if(filled($tier['closing']))
                        <p class="mt-4 text-sm font-medium text-gray-800">{{ $tier['closing'] }}</p>
                    @endif

                    @if(filled($tier['audience']))
                        <p class="mt-4 text-sm text-muted">{{ $tier['audience'] }}</p>
                    @endif
                </x-ui.panel>
            @endforeach
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-ui.button :href="localized_route('contact.index')" :label="__('Contact us')"/>
    </x-layout.section>
</x-app-layout>
