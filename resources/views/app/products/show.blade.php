<x-app-layout :page="$page">

    <x-h1 :title="$headline ?? $name"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-section>
        <x-content :content="$content"/>
    </x-section>

    @if(!empty($deploymentOptions))
        <x-section>
            <x-h2 :title="$deploymentHeading"/>
            @if($deploymentIntro)
                <p class="mt-2">{{ $deploymentIntro }}</p>
            @endif
            <x-list-grid class-attributes="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($deploymentOptions as $option)
                    <x-deployment-option-card :title="$option['title']" :description="$option['description']"/>
                @endforeach
            </x-list-grid>
        </x-section>
    @endif

    @if(collect(['DMS/ECM', 'DocuWare'])->diff($tags)->isEmpty())
        <x-docuware-showme/>
    @endif

    @if($ctaHeading)
        <x-cta-band
                :title="$ctaHeading"
                :body="$ctaBody"
                :button-label="__('Contact us')"
                :button-href="localized_route('contact.index')"/>
    @endif

</x-app-layout>