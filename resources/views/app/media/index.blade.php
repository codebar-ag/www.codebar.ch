<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Press')"
        :title="__('Media')"
        :teaser="__('Press resources, brand notes and media contact details.')"
    />

    <x-ui.section>
        <x-ui.grid columns="2">
            <x-ui.feature-card
                :title="__('Media contact')"
                :teaser="__('For interviews and public communication, contact us directly by email.')"
            >
                <x-ui.eyebrow :text="__('Press Inquiries')" class-attributes="-mt-2 mb-3 order-first" />
                <x-ui.button
                    href="mailto:{{ config('site.contact.email') }}?subject=Media%20Request"
                    :label="__('Contact media desk')"
                    variant="secondary"
                />
            </x-ui.feature-card>

            <x-ui.feature-card
                :title="__('Logos and visuals')"
                :teaser="__('Use official logos and screenshots with attribution when publishing references to our work.')"
            >
                <x-ui.eyebrow :text="__('Brand Assets')" class-attributes="-mt-2 mb-3 order-first" />
            </x-ui.feature-card>
        </x-ui.grid>
    </x-ui.section>
</x-app-layout>
