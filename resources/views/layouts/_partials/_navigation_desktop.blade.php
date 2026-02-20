<div class="hidden lg:flex justify-between w-full">
    <div class="flex items-center gap-2">
        <x-nav-link :href="localized_route('start.index')" label="{{ __('Home') }}" :active="request()->routeIs('*.start.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('news.index')" label="{{ __('News') }}" :active="request()->routeIs('*.news.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('about-us.index')" label="{{ __('About us') }}" :active="request()->routeIs('*.about-us.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('services.index')" label="{{ __('Services') }}" :active="request()->routeIs('*.services.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('products.index')" label="{{ __('Products') }}" :active="request()->routeIs('*.products.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('technologies.index')" label="{{ __('Technologies') }}" :active="request()->routeIs('*.technologies.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('open-source.index')" label="{{ __('Open Source') }}" :active="request()->routeIs('*.open-source.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('ai.index')" label="{{ __('AI') }}" :active="request()->routeIs('*.ai.*')" />
        <span class="text-gray-400">|</span>
        <x-nav-link :href="localized_route('contact.index')" label="{{ __('Contact') }}" :active="request()->routeIs('*.contact.*')" />
    </div>
</div>
