<x-app-layout :page="$page">
    <x-blocks.editorial-hero
        :title="__('Jobs')"
        :teaser="__('Join us and build high-impact digital experiences with a multidisciplinary team.')"
        :eyebrow="__('Careers')"
    />

    <x-ui.section class-attributes="editorial-divider">
        <x-ui.grid columns="3">
            <x-ui.feature-card :title="__('Senior Engineer')" :teaser="__('Own architecture and implementation across meaningful client products.')">
                <x-ui.button href="mailto:info@codebar.ch?subject=Job%20Application" :label="__('Apply now')" variant="outline" class-attributes="mt-2" />
            </x-ui.feature-card>
            <x-ui.feature-card :title="__('Product Designer')" :teaser="__('Shape product narratives, interaction systems and visual quality.')">
                <x-ui.button href="mailto:info@codebar.ch?subject=Job%20Application" :label="__('Apply now')" variant="outline" class-attributes="mt-2" />
            </x-ui.feature-card>
            <x-ui.feature-card :title="__('Project Lead')" :teaser="__('Bridge strategy, delivery and communication in complex engagements.')">
                <x-ui.button href="mailto:info@codebar.ch?subject=Job%20Application" :label="__('Apply now')" variant="outline" class-attributes="mt-2" />
            </x-ui.feature-card>
        </x-ui.grid>
    </x-ui.section>
</x-app-layout>