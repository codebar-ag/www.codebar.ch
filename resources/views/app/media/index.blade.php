<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Media')"
        :teaser="__('Press resources, brand notes and media contact details.')"
        :eyebrow="__('Press')"
    />

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.grid>
            <x-ui.card>
                <x-ui.eyebrow text="{{ __('Press Inquiries') }}" />
                <x-ui.headline :title="__('Media contact')" level="h3" class-attributes="mt-2" />
                <x-ui.text class-attributes="mt-2">{{ __('For interviews and public communication, contact us directly by email.') }}</x-ui.text>
                <x-ui.button href="mailto:info@paperflakes.ch?subject=Media%20Request" :label="__('Contact media desk')" variant="outline" class-attributes="mt-4" />
            </x-ui.card>
            <x-ui.card>
                <x-ui.eyebrow text="{{ __('Brand Assets') }}" />
                <x-ui.headline :title="__('Logos and visuals')" level="h3" class-attributes="mt-2" />
                <x-ui.text class-attributes="mt-2">{{ __('Use official logos and screenshots with attribution when publishing references to our work.') }}</x-ui.text>
            </x-ui.card>
        </x-ui.grid>
    </x-ui.section>
</x-app-layout>