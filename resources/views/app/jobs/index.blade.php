<x-app-layout :page="$page">
    <x-layout.page-header :title="__('Jobs')" :intro="__('Jobs page header')"/>

    <x-layout.section>
        <x-h2 :title="__('Jobs intro heading')"/>
        <x-ui.prose>
            <p>{{ __('Jobs intro') }}</p>
        </x-ui.prose>
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Jobs training heading')"/>
        <x-ui.prose>
            <p>{{ __('Jobs training body') }}</p>
        </x-ui.prose>

        @foreach($inProcessPositions as $position)
            <x-ui.panel class="mt-6 px-6 py-6">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                    <x-h3 :title="$position->title" class="mb-0"/>
                    <x-ui.badge variant="notice" class="gap-1.5">
                        <span class="size-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                        {{ __('Job status in process') }}
                    </x-ui.badge>
                </div>
                <p class="mt-3 max-w-prose text-base text-muted">{{ __('Job in process note') }}</p>
            </x-ui.panel>
        @endforeach
    </x-layout.section>

    <x-layout.section>
        <x-h2 :title="__('Jobs open positions heading')"/>
        @forelse($openPositions as $position)
            <x-ui.panel @class(['px-6 py-6', 'mt-4' => ! $loop->first])>
                <x-h3 :title="$position->title"/>
                @if(filled($position->teaser))
                    <p class="text-gray-800">{{ $position->teaser }}</p>
                @endif
                @if($position->route_name)
                    <x-ui.arrow-link :href="localized_route($position->route_name)" :label="__('Details and application')" class="mt-4"/>
                @endif
            </x-ui.panel>
        @empty
            <p class="text-gray-800">{{ __('Jobs no open positions') }}</p>
        @endforelse
    </x-layout.section>
</x-app-layout>
