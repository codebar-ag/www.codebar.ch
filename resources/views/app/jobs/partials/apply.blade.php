<x-ui.panel class="px-4 py-6 sm:px-6">
    <p class="text-base text-gray-800">{{ __('Internship apply body') }}</p>

    <form method="POST" action="{{ localized_route('jobs.internship.request.store') }}" class="mt-6">
        @csrf
        <x-honeypot/>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <x-form.field name="email" :for="$fieldId" :label="__('Email')" class="grow">
                <x-form.input name="email" :id="$fieldId" type="email" required placeholder="max@example.ch"/>
            </x-form.field>

            <x-ui.button :label="__('Start my application')" class="w-full sm:w-auto"/>
        </div>
    </form>

    <p class="mt-4 text-sm text-muted">
        {{ __('Internship apply privacy') }}
        <x-ui.link :href="localized_route('legal.privacy.index')" target="_blank" class="inline-flex items-center gap-1 underline">
            {{ __('Privacy policy') }}
            <x-icon.external-link class="size-3.5 shrink-0"/>
        </x-ui.link>
    </p>
</x-ui.panel>
