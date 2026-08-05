<x-zunscan.layout :title="$title" :description="$description" :image="$image">
    <x-zunscan.components.title :title="__('zunscan.legal.privacy_title')" :subtitle="__('zunscan.legal.privacy_subtitle')"/>

    <x-zunscan.components.section>
        <x-zunscan.components.card class="mx-auto max-w-3xl p-6 sm:p-10">
            <div class="legal-prose">
                {!! $body !!}
            </div>
        </x-zunscan.components.card>
    </x-zunscan.components.section>
</x-zunscan.layout>
