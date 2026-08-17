<x-app-layout :page="$page" :schema="$schema">

    <x-layout.page-header
            :title="__('components.docuware.dms_ecm.title')"
            :intro="__('components.docuware.dms_ecm.lead')"
            :breadcrumbs="[
                ['label' => __('Services'), 'url' => localized_route('services.index')],
                ['label' => __('components.docuware.dms_ecm.crumb')],
            ]">
        <x-slot:eyebrow>
            <x-ui.badge :label="__('components.docuware.label')"/>
        </x-slot:eyebrow>
    </x-layout.page-header>

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-layout.section-header
                :title="__('components.docuware.dms_ecm.export_title')"
                :intro="__('components.docuware.dms_ecm.export_teaser')"/>

        <x-ui.arrow-link
                :href="localized_route('services.dms-ecm.docuware-export.index')"
                :label="__('components.docuware.dms_ecm.to_export')"/>
    </x-layout.section>

</x-app-layout>
