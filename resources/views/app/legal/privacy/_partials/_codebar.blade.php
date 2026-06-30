<x-h1 :title="__('Privacy')"/>

<p class="mb-2 text-gray-600">{{ __('Last updated at: :date', ['date' => '2026-06-30']) }}</p>

<x-section>
    <x-h2 :title="__('Privacy controller heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy controller body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy scope heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy scope body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy data collected heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy data collected intro') }}</p>
        <ul>
            <li>{{ __('Privacy data collected logs') }}</li>
            <li>{{ __('Privacy data collected session') }}</li>
            <li>{{ __('Privacy data collected analytics') }}</li>
            <li>{{ __('Privacy data collected errors') }}</li>
        </ul>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy purpose heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy purpose body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy retention heading')"/>
    <x-legal.prose>
        <ul>
            <li>{{ __('Privacy retention session') }}</li>
            <li>{{ __('Privacy retention logs') }}</li>
            <li>{{ __('Privacy retention analytics') }}</li>
            <li>{{ __('Privacy retention errors') }}</li>
        </ul>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy rights heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy rights body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy security heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy security body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy changes heading')"/>
    <x-legal.prose>
        <p>{{ __('Privacy changes body') }}</p>
    </x-legal.prose>
</x-section>

<x-section>
    <x-h2 :title="__('Privacy contact heading')"/>
    <x-legal.prose>
        <p>
            {{ __('Privacy contact body') }}
            <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}" classAttributes="font-medium no-underline"/>
        </p>
    </x-legal.prose>
</x-section>
