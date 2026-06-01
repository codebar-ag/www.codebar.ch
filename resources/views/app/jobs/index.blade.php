<x-app-layout :page="$page">
    <x-ui.hero
        :eyebrow="__('Careers')"
        :title="__('Jobs')"
        :teaser="__('Join us and build high-impact digital experiences with a multidisciplinary team.')"
    />

    <x-ui.section>
        @php
            $jobs = [
                ['title' => __('Senior Engineer'), 'teaser' => __('Own architecture and implementation across meaningful client products.')],
                ['title' => __('Product Designer'), 'teaser' => __('Shape product narratives, interaction systems and visual quality.')],
                ['title' => __('Project Lead'), 'teaser' => __('Bridge strategy, delivery and communication in complex engagements.')],
            ];
        @endphp

        <x-ui.grid columns="3">
            @foreach($jobs as $job)
                <x-ui.feature-card :title="$job['title']" :teaser="$job['teaser']">
                    <x-ui.button
                        href="mailto:{{ config('site.contact.email') }}?subject=Job%20Application:%20{{ urlencode($job['title']) }}"
                        :label="__('Apply now')"
                        variant="secondary"
                    />
                </x-ui.feature-card>
            @endforeach
        </x-ui.grid>
    </x-ui.section>
</x-app-layout>
