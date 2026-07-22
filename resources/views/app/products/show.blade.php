<x-app-layout :page="$page">

    <x-h1 :title="$headline ?? $name"/>
    <x-h1-teaser :teaser="$teaser"/>

    <x-layout.section>
        <x-ui.prose>
            {!! $content !!}
        </x-ui.prose>
    </x-layout.section>

    @if(!empty($features))
        <x-layout.section>
            <x-layout.section-header :title="$featuresHeading" :intro="$featuresIntro"/>
            <x-layout.grid :cols="2" gap="gap-x-8 gap-y-6" class="mt-6">
                @foreach($features as $feature)
                    <x-feature-block :title="$feature['title']" :description="$feature['description']"/>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endif

    @if(!empty($deploymentOptions))
        <x-layout.section>
            <x-layout.section-header :title="$deploymentHeading" :intro="$deploymentIntro"/>
            <x-layout.grid :cols="3" gap="gap-8" class="mt-6">
                @foreach($deploymentOptions as $option)
                    <x-feature-block :title="$option['title']" :description="$option['description']"/>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endif

    @if(collect(['DMS/ECM', 'DocuWare'])->diff($tags)->isEmpty())
        <x-docuware-showme/>
    @endif

    @if($ctaHeading)
        <x-band.cta-band :title="$ctaHeading" :body="$ctaBody">
            <x-ui.button variant="primary" :href="localized_route('contact.index')" :label="__('Contact us')"/>
        </x-band.cta-band>
    @endif

</x-app-layout>
