<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Co-Working at codebar')"
                           :intro="__('A professional workspace in Oberwil — quiet, fully equipped, and set inside a tech team instead of alone at home.')"
                           :page="$page"/>

    <x-layout.section>
        <x-ui.prose>
            <p>{{ __('codebar Solutions AG offers fully-equipped workstations in a professional co-working environment in Oberwil. Ideal for freelancers, start-ups, and remote employees in the Basel region looking for a quiet, professional alternative to working from home — with first-class infrastructure and flexible terms.') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Location')"/>
        <x-card.address-card
                :title="__('Oberwil')"
                :lines="['codebar Solutions AG', 'Langegasse 39', 'CH-4104 Oberwil']"
                link-href="https://maps.app.goo.gl/1ndrUgUvw2pxxekUA"
                link-label="{{ __('Google Maps') }} — {{ __('Oberwil') }}"/>
    </x-layout.section>

    <x-layout.section>
        <x-layout.section-header :title="__('What’s included')"/>
        <x-layout.grid :cols="3" gap="gap-6">
            @foreach($services as $service)
                <x-feature-block :title="$service['title']" :description="$service['teaser']"/>
            @endforeach
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-layout.section-header :title="__('Pricing')"/>

        <x-ui.panel class="max-w-md px-6 py-6">
            <div class="text-lg font-semibold text-gray-800">{{ $pricing['name'] }}</div>
            <div class="mt-1 text-title font-bold text-gray-900">
                CHF {{ number_format($pricing['price_chf'], 0) }}
                <span class="text-base font-normal text-muted">/ {{ $pricing['period'] }}</span>
            </div>
            <p class="mt-2 text-muted">{{ __('All-inclusive — just bring your laptop.') }}</p>
        </x-ui.panel>

        <div class="mt-8 grid gap-8 sm:grid-cols-2">
            <div>
                <x-h3 :title="__('Optional add-ons')"/>
                <dl class="divide-y divide-border-soft">
                    @foreach($optionalServices as $service)
                        <div class="flex items-baseline justify-between gap-6 py-2">
                            <dt class="text-sm font-medium text-gray-800">{{ $service['name'] }}</dt>
                            <dd class="text-sm text-muted text-right">{{ $service['price'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
            <div>
                <x-h3 :title="__('Rental conditions')"/>
                <dl class="divide-y divide-border-soft">
                    <div class="flex items-baseline justify-between gap-6 py-2">
                        <dt class="text-sm font-medium text-gray-800">{{ __('Minimum term') }}</dt>
                        <dd class="text-sm text-muted text-right">{{ $rentalConditions['minimum_months'] }} {{ __('months') }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-6 py-2">
                        <dt class="text-sm font-medium text-gray-800">{{ __('Notice period') }}</dt>
                        <dd class="text-sm text-muted text-right">{{ $rentalConditions['notice_months'] }} {{ __('months') }}</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-6 py-2">
                        <dt class="text-sm font-medium text-gray-800">{{ __('Deposit') }}</dt>
                        <dd class="text-sm text-muted text-right">{{ $rentalConditions['deposit_text'] }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-ui.button :href="localized_route('contact.index')" :label="__('Book a viewing')"/>
    </x-layout.section>
</x-app-layout>
