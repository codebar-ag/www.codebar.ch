<x-app-layout :page="$page">

    <x-layout.page-header :title="$name" :intro="$teaser" :breadcrumbs="[
        ['label' => __('Services'), 'url' => localized_route('services.index')],
        ['label' => $name],
    ]"/>

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>
    </x-layout.section>

</x-app-layout>
