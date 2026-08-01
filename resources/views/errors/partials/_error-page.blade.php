<x-app-layout :page="null">
    <x-layout.page-header :title="$title" :intro="$message" :breadcrumbs="[]"/>

    <x-layout.page-note>{{ __('errors.status_label', ['code' => $statusCode]) }}</x-layout.page-note>

    <x-layout.section>
        <div class="flex flex-col sm:flex-row gap-3">
            <x-ui.button :href="localized_route('start.index')" :label="__('errors.back_home')"/>
            <x-ui.button :href="localized_route('contact.index')" variant="outline" :label="__('Contact')"/>
        </div>
    </x-layout.section>
</x-app-layout>
