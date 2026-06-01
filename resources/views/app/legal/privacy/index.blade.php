<x-app-layout :page="$page">
    <x-blocks.legal-document
        :title="__('Privacy')"
        :intro="__('How we collect, process and protect your information.')"
        :sections="[
            ['heading' => __('Data we process'), 'content' => __('We process only data needed to deliver services, maintain security and answer inquiries.')],
            ['heading' => __('Purpose and retention'), 'content' => __('Data is used strictly for project delivery, communication and legal obligations, with retention limited to required periods.')],
            ['heading' => __('Your rights'), 'content' => __('You may request access, correction or deletion according to applicable data protection laws.')],
        ]"
    />
</x-app-layout>
