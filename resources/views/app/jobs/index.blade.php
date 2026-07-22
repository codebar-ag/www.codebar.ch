<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Jobs')" :page="$page"/>

    <x-layout.section>
        <x-ui.prose>
            <p>{{ __('Jobs intro') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Jobs open positions heading')"/>
        <p class="text-gray-800">{{ __('Jobs no open positions') }}</p>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Jobs spontaneous heading')"/>
        <x-ui.prose>
            <p>
                {{ __('Jobs spontaneous body') }}
                <x-ui.link href="mailto:info@codebar.ch" label="{{ __('info(at)codebar.ch') }}" class="font-medium no-underline"/>
            </p>
        </x-ui.prose>
    </x-layout.section>
</x-app-layout>
