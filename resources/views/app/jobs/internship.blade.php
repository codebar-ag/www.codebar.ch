<x-app-layout :page="$page">
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
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Internship focus heading')"/>
        <x-ui.prose>
            <p>{{ __('Internship focus body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section id="bewerbung" class="scroll-mt-24">
        <x-h2 :title="__('Internship apply heading')"/>
        @include('app.jobs.partials.apply', ['fieldId' => 'email'])
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
