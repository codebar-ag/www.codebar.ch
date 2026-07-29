<x-app-layout :page="$page">
    <div class="mt-6">
        <x-ui.link :href="localized_route('network.index')" class="text-sm text-muted">
            &larr; {{ __('Network') }}
        </x-ui.link>
    </div>

    <x-h1 title="BaselHack"/>

    <div class="flex flex-wrap items-center gap-2">
        @if($network->tier_label)
            <x-ui.badge :label="$network->tier_label"/>
        @endif
        <x-ui.badge :label="$network->status->getLabel()"/>
    </div>

    <x-layout.section>
        <p class="max-w-2xl text-gray-800">
            {{ __('For several years we have been supporting BaselHack — the largest hackathon in the region — with infrastructure and material, and for the last two years as a Silver Sponsor.') }}
        </p>

        <p class="mt-4 max-w-2xl text-gray-800">
            {{ __('An entire weekend of building, learning and shipping: teams from the region take on real challenges and turn them into working prototypes. We are proud to help make that possible.') }}
        </p>
    </x-layout.section>

    <x-layout.section>
        <x-layout.section-header :title="__('Facts')"/>
        <x-layout.grid :cols="3" class="mt-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-muted">{{ __('Role') }}</p>
                <p class="mt-1 text-gray-800">{{ __('Silver Sponsor, infrastructure') }}</p>
            </div>
            <div>
                <p class="text-sm uppercase tracking-wide text-muted">{{ __('Format') }}</p>
                <p class="mt-1 text-gray-800">{{ __('The largest hackathon in the Basel region') }}</p>
            </div>
            <div>
                <p class="text-sm uppercase tracking-wide text-muted">{{ __('Website') }}</p>
                @if($network->website)
                    <a href="{{ $network->website }}" target="_blank" rel="noopener noreferrer"
                       class="mt-1 block rounded-pill focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand text-gray-800 transition hover:text-brand">
                        {{ $network->websiteHost() }} &nearr;
                    </a>
                @endif
            </div>
        </x-layout.grid>
    </x-layout.section>

    @if($users->isNotEmpty())
        <x-layout.section>
            <x-layout.section-header :title="__('Contact persons')"/>
            <x-layout.grid :cols="2" class="mt-4 max-w-2xl">
                @foreach($users as $user)
                    <div class="rounded-panel border border-border p-4">
                        <x-card.network-user-card :user="$user" :divided="false"/>
                    </div>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endif
</x-app-layout>
