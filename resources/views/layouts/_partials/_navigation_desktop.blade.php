@php use App\Enums\LocaleEnum; @endphp

<div class="hidden lg:flex justify-between w-full">
    <div class="flex gap-2">

        <x-a :href="localized_route('start.index')" label="{{ __('Home') }}" classAttributes="text-xl md:text-2xl" />

        <span class="text-gray-500">|</span>

        @if ($configuration?->section_news)
            <x-a :href="localized_route('news.index')" label="{{ __('News') }}" classAttributes="text-xl md:text-2xl" />
            <span class="text-gray-500">|</span>
        @endif

        <x-a :href="localized_route('about-us.index')" label="{{ __('About us') }}" classAttributes="text-xl md:text-2xl" />

        <span class="text-gray-500">|</span>

        @if ($configuration?->section_services)
            <x-a :href="localized_route('services.index')" label="{{ __('Services') }}" classAttributes="text-xl md:text-2xl" />
            <span class="text-gray-500">|</span>
        @endif

        @if ($configuration?->section_products)
            <x-a :href="localized_route('products.index')" label="{{ __('Products') }}" classAttributes="text-xl md:text-2xl" />
            <span class="text-gray-500">|</span>
        @endif

        @if ($configuration?->section_technologies)
            <x-a :href="localized_route('technologies.index')" label="{{ __('Technologies') }}" classAttributes="text-xl md:text-2xl" />
            <span class="text-gray-500">|</span>
        @endif

        @if ($configuration?->section_open_source)
            <x-a :href="localized_route('open-source.index')" label="{{ __('Open Source') }}" classAttributes="text-xl md:text-2xl" />
            <span class="text-gray-500">|</span>
        @endif

        <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}" classAttributes="text-xl md:text-2xl" />

    </div>
</div>
