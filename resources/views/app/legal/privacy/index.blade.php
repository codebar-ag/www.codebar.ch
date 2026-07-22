<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Privacy')" :intro="__('Privacy page intro')" :page="$page"/>

    <p class="mb-2 text-muted">{{ __('Last updated at: :date', ['date' => __('Privacy last updated date')]) }}</p>

    <x-layout.section>
        <x-h2 :title="__('Privacy controller heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy controller body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy scope heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy scope body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy data collected heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy data collected intro') }}</p>
            <ul>
                <li>{{ __('Privacy data collected logs') }}</li>
                <li>{{ __('Privacy data collected session') }}</li>
                <li>{{ __('Privacy data collected analytics') }}</li>
                <li>{{ __('Privacy data collected errors') }}</li>
            </ul>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy purpose heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy purpose body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy retention heading')"/>
        <x-ui.prose variant="legal">
            <ul>
                <li>{{ __('Privacy retention session') }}</li>
                <li>{{ __('Privacy retention logs') }}</li>
                <li>{{ __('Privacy retention analytics') }}</li>
                <li>{{ __('Privacy retention errors') }}</li>
            </ul>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy rights heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy rights body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy security heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy security body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy changes heading')"/>
        <x-ui.prose variant="legal">
            <p>{{ __('Privacy changes body') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Privacy contact heading')"/>
        <x-ui.prose variant="legal">
            <p>
                {{ __('Privacy contact body') }}
                <x-ui.link :href="localized_route('contact.index')" label="{{ __('Contact') }}" class="font-medium no-underline"/>
            </p>
        </x-ui.prose>
    </x-layout.section>
</x-app-layout>
