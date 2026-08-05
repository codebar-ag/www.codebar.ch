<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.contact.title')" :subtitle="__('zunscan.contact.subtitle')"/>

    <x-zunscan.components.section>
        <x-zunscan.components.eyebrow>{{ __('zunscan.contact.eyebrow') }}</x-zunscan.components.eyebrow>
        <h2 class="mt-3 text-title text-balance text-zunscan-dark-gray">{{ __('zunscan.contact.heading') }}</h2>
        <p class="mt-4 max-w-3xl text-lead font-light text-zunscan-light-gray">{{ __('zunscan.contact.body') }}</p>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            @foreach(config('zunscan.people') as $person)
                <x-zunscan.components.person
                    :name="$person['name']"
                    :company="$person['company']"
                    :email="$person['email']"
                    :phone="$person['phone']"
                    :website="$person['website']"
                    :website-label="$person['website_label']"
                    :linkedin="$person['linkedin']"
                    :image="$person['image']"
                />
            @endforeach
        </div>

        <h2 class="mt-16 text-title text-zunscan-dark-gray">{{ __('zunscan.contact.locations_title') }}</h2>

        <div class="mt-6 grid gap-6 sm:grid-cols-2">
            @foreach(config('zunscan.locations') as $location)
                <x-zunscan.components.card>
                    <p class="text-heading text-zunscan-dark-gray">{{ $location['company'] }}</p>
                    <address class="mt-2 not-italic font-light text-zunscan-light-gray">
                        {{ $location['street'] }}<br>
                        {{ $location['city'] }}
                    </address>
                </x-zunscan.components.card>
            @endforeach
        </div>
    </x-zunscan.components.section>
</x-zunscan.layout>
