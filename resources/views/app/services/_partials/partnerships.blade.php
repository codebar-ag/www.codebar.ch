<x-ui.section class-attributes="editorial-divider">
    <x-ui.eyebrow text="{{ __('Partnerships') }}" />
    <x-ui.headline :title="__('Trusted ecosystem partners')" level="h2" class-attributes="mt-2 mb-4" />
    @include('app.services._parials.partnerships')
</x-ui.section>
