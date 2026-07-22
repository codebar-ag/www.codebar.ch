@php
    $sections = ['who_we_are', 'what_we_do', 'how_we_work'];
@endphp

<x-h1 :title="__('Welcome')" />

@foreach ($sections as $key)
    <x-layout.section>
        <x-h2 :title="__('components.intro.' . $key . '.title')"/>
        <p>{{ __('components.intro.' . $key . '.text') }}</p>
    </x-layout.section>
@endforeach
