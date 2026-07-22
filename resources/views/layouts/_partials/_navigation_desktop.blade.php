@php use App\Enums\LocaleEnum; @endphp

<div class="hidden lg:flex justify-between w-full">
    <div class="flex gap-2">

        <x-a :href="localized_route('start.index')" label="{{ __('Home') }}" classAttributes="text-xl md:text-2xl" />

        <span class="text-gray-300">|</span>

        <x-a :href="localized_route('about-us.index')" label="{{ __('Team') }}" classAttributes="text-xl md:text-2xl" />

        {{-- <span class="text-gray-300">|</span>

        <x-a :href="localized_route('technologies.index')" label="{{ __('Technologies') }}" classAttributes="text-xl md:text-2xl" /> --}}

        <span class="text-gray-300">|</span>

        <x-a :href="localized_route('ai.index')" label="{{ __('AI') }}" classAttributes="text-xl md:text-2xl" />

        <span class="text-gray-300">|</span>

        <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}" classAttributes="text-xl md:text-2xl" />

    </div>
</div>
