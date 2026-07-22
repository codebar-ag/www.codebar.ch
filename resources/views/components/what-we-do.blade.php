@php
    $items = ['concept', 'development', 'dms'];
@endphp

<x-layout.section>
    <x-h2 :title="__('components.what_we_do.title')" />
    <div class="mt-6 flex flex-col gap-6">
        @foreach ($items as $key)
            <div>
                <x-h3 :title="__('components.what_we_do.items.' . $key . '.title')" />
                <p>{{ __('components.what_we_do.items.' . $key . '.description') }}</p>
            </div>
        @endforeach
    </div>
</x-layout.section>
