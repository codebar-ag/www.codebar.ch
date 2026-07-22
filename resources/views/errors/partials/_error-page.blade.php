{{-- Shared shell for all HTTP error pages: same app layout as regular pages (header, footer, CTAs). --}}
<x-app-layout :page="null">
    <x-layout.page-header :title="$title" :intro="$message"/>

    <p class="mb-2 text-muted">{{ __('errors.status_label', ['code' => $statusCode]) }}</p>

    <x-layout.section>
        <div class="flex flex-col sm:flex-row gap-3">
            <x-ui.button :href="localized_route('start.index')" :label="__('errors.back_home')"/>
            <x-ui.button :href="localized_route('contact.index')" variant="outline" :label="__('Contact')"/>
        </div>
    </x-layout.section>
</x-app-layout>
