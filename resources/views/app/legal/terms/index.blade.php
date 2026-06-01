<x-app-layout :page="$page">
    <x-blocks.legal-document
        :title="__('Terms')"
        :intro="__('Terms and conditions governing the use of our services.')"
        :sections="[
            ['heading' => __('Scope of services'), 'content' => __('Engagement scope, responsibilities and deliverables are defined in project agreements and statements of work.')],
            ['heading' => __('Liability and warranties'), 'content' => __('Liability is limited to legally permitted extent and project-specific contractual terms.')],
            ['heading' => __('Intellectual property'), 'content' => __('Ownership and usage rights are regulated by contract; third-party licenses remain subject to their respective terms.')],
        ]"
    />
</x-app-layout>
