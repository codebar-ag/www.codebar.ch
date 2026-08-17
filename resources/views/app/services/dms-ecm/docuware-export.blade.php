<x-app-layout :page="$page" :schema="$schema">

    <x-layout.page-header
            :title="__('components.docuware.export.title')"
            :intro="__('components.docuware.export.lead')"
            :breadcrumbs="[
                ['label' => __('Services'), 'url' => localized_route('services.index')],
                ['label' => __('components.docuware.dms_ecm.crumb'), 'url' => localized_route('services.dms-ecm.index')],
                ['label' => __('components.docuware.export.crumb')],
            ]">
        <x-slot:eyebrow>
            <x-ui.badge :label="__('components.docuware.label')"/>
        </x-slot:eyebrow>
    </x-layout.page-header>

    <x-layout.section>
        <x-h2 :title="__('components.docuware.export.cases.title')"/>

        <ul class="divide-y divide-border-soft">
            @foreach(__('components.docuware.export.cases.items') as $case)
                <li class="py-3.5">{!! $case !!}</li>
            @endforeach
        </ul>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('components.docuware.export.modes.title')"/>

        <x-layout.grid>
            @foreach(['once', 'scheduled'] as $mode)
                <x-ui.panel class="flex flex-col gap-3 p-6">
                    <span aria-hidden="true" class="block h-1.5 w-10 rounded-pill bg-brand"></span>
                    <h3 class="text-subheading font-semibold text-balance text-gray-900">{{ __('components.docuware.export.modes.'.$mode.'.title') }}</h3>
                    <p class="text-base">{{ __('components.docuware.export.modes.'.$mode.'.body') }}</p>
                    <p class="mt-auto text-sm text-muted">{{ __('components.docuware.export.modes.'.$mode.'.for') }}</p>
                </x-ui.panel>
            @endforeach
        </x-layout.grid>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('components.docuware.export.scope.title')"/>

        <dl class="divide-y divide-border-soft">
            @foreach(__('components.docuware.export.scope.items') as $entry)
                <div class="grid gap-x-6 py-3.5 sm:grid-cols-[11rem_minmax(0,1fr)]">
                    <dt class="font-semibold text-gray-900">{{ $entry['term'] }}</dt>
                    <dd>{{ $entry['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('components.docuware.export.access.title')"/>

        <ul class="divide-y divide-border-soft">
            @foreach(__('components.docuware.export.access.items') as $item)
                <li class="py-3.5">{!! $item !!}</li>
            @endforeach
        </ul>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('components.docuware.export.process.title')"/>

        <ol class="space-y-5">
            @foreach(__('components.docuware.export.process.items') as $step)
                <li class="relative pl-11">
                    <span aria-hidden="true"
                          class="absolute top-0.5 left-0 grid size-7 place-items-center rounded-full bg-brand text-sm font-medium text-white">{{ $loop->iteration }}</span>
                    <span class="block font-semibold text-gray-900">{{ $step['title'] }}</span>
                    <span class="mt-0.5 block text-base">{{ $step['body'] }}</span>
                </li>
            @endforeach
        </ol>
    </x-layout.section>

    <x-layout.section>
        <x-ui.panel class="max-w-3xl px-6 py-5">
            <h3 class="mb-2 font-semibold text-gray-900">{{ __('components.docuware.export.timing.title') }}</h3>
            <p class="text-base">{{ __('components.docuware.export.timing.body') }}</p>
        </x-ui.panel>
    </x-layout.section>

    <x-band.cta-band
            :title="__('components.docuware.export.cta.title')"
            :body="__('components.docuware.export.cta.body')">
        <x-ui.button variant="primary" :href="localized_route('contact.index')" :label="__('Contact')"/>
        <x-ui.button variant="outline" :href="localized_route('services.dms-ecm.index')"
                     :label="__('components.docuware.export.cta.back')"/>
    </x-band.cta-band>

</x-app-layout>
