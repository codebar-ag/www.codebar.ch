<x-app-layout :page="$page" :schema="$schema">
    <x-layout.page-header
            :title="__('Internship title')"
            :intro="__('Internship page header')"
            :breadcrumbs="[
                ['label' => __('Jobs'), 'url' => localized_route('jobs.index')],
                ['label' => __('Internship title')],
            ]"/>

    <x-ui.toast :message="session('status')"/>

    <x-layout.section>
        <x-h2 :title="__('Internship journey heading')"/>
        <p class="mb-6 text-lg font-medium text-gray-800">{{ __('Internship journey intro') }}</p>

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.panel class="px-4 py-6 sm:px-6">
                <p class="text-eyebrow uppercase text-brand">{{ __('Internship phase first') }}</p>
                <x-h3 :title="__('Internship phase plan heading')" class="mt-3"/>
                <p class="text-base text-gray-800">{{ __('Internship phase plan body') }}</p>
            </x-ui.panel>

            <x-ui.panel class="px-4 py-6 sm:px-6">
                <p class="text-eyebrow uppercase text-brand">{{ __('Internship phase then') }}</p>
                <x-h3 :title="__('Internship phase build heading')" class="mt-3"/>
                <p class="text-base text-gray-800">{{ __('Internship phase build body') }}</p>
            </x-ui.panel>

            <x-ui.panel class="px-4 py-6 sm:px-6">
                <p class="text-eyebrow uppercase text-brand">{{ __('Internship phase after') }}</p>
                <x-h3 :title="__('Internship phase run heading')" class="mt-3"/>
                <p class="text-base text-gray-800">{{ __('Internship phase run body') }}</p>
            </x-ui.panel>
        </div>

        <p class="mt-6 text-base text-muted">{{ __('Internship journey outro') }}</p>

        <div class="mt-8">
            <x-h3 :title="__('Internship outcome heading')"/>
            <div class="flex flex-wrap gap-2">
                @foreach(['Portale', 'Schnittstellen', 'Automatisierungen', 'Webseiten', 'Dashboards'] as $outcome)
                    <span class="inline-flex items-center rounded-pill border border-border bg-surface px-4 py-1.5 text-sm font-medium text-gray-800">{{ $outcome }}</span>
                @endforeach
            </div>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Internship bring heading')"/>
        <x-ui.prose>
            <p>{{ __('Internship bring intro') }}</p>
        </x-ui.prose>
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="inline-flex items-center rounded-pill border border-brand/30 bg-white px-4 py-1.5 text-sm font-medium text-gray-800">{{ __('Internship bring initiative') }}</span>
            <span class="inline-flex items-center rounded-pill border border-brand/30 bg-white px-4 py-1.5 text-sm font-medium text-gray-800">{{ __('Internship bring learning') }}</span>
            <span class="inline-flex items-center rounded-pill border border-brand/30 bg-white px-4 py-1.5 text-sm font-medium text-gray-800">{{ __('Internship bring passion') }}</span>
        </div>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Internship focus heading')"/>
        <x-ui.prose>
            <p>{{ __('Internship focus body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section id="bewerbung" class="scroll-mt-24">
        <x-h2 :title="__('Internship apply heading')"/>
        @if($position?->isOpen())
            @include('app.jobs.partials.apply', ['fieldId' => 'email'])
        @else
            <x-ui.panel class="px-4 py-6 sm:px-6">
                <p class="text-base text-gray-800">{{ __('Internship closed body') }}</p>
            </x-ui.panel>
        @endif
    </x-layout.section>

    @if($mentors->isNotEmpty())
        <x-layout.section>
            <x-h2 :title="__('Internship team heading')"/>
            <x-ui.prose>
                <p>{{ __('Internship team body') }}</p>
            </x-ui.prose>

            <x-layout.grid :cols="2" class="mt-6">
                @foreach($mentors as $mentor)
                    <x-card.person-card
                            :name="$mentor->name"
                            :role="$mentor->role"
                            :icons="$mentor->icons"
                            :image="$mentor->image"/>
                @endforeach
            </x-layout.grid>
        </x-layout.section>
    @endif
</x-app-layout>
