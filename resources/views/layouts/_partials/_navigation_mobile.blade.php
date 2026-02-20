<div x-show="open" x-transition x-cloak class="mt-4 space-y-2">
    <x-nav-mobile-link :href="localized_route('start.index')" label="{{ __('Home') }}" :active="request()->routeIs('*.start.*')" />
    <x-nav-mobile-link :href="localized_route('news.index')" label="{{ __('News') }}" :active="request()->routeIs('*.news.*')" />
    <x-nav-mobile-link :href="localized_route('about-us.index')" label="{{ __('About us') }}" :active="request()->routeIs('*.about-us.*')" />
    <x-nav-mobile-link :href="localized_route('services.index')" label="{{ __('Services') }}" :active="request()->routeIs('*.services.*')" />
    <x-nav-mobile-link :href="localized_route('products.index')" label="{{ __('Products') }}" :active="request()->routeIs('*.products.*')" />
    <x-nav-mobile-link :href="localized_route('technologies.index')" label="{{ __('Technologies') }}" :active="request()->routeIs('*.technologies.*')" />
    <x-nav-mobile-link :href="localized_route('open-source.index')" label="{{ __('Open Source') }}" :active="request()->routeIs('*.open-source.*')" />
    <x-nav-mobile-link :href="localized_route('ai.index')" label="{{ __('AI') }}" :active="request()->routeIs('*.ai.*')" />
    <x-nav-mobile-link :href="localized_route('contact.index')" label="{{ __('Contact') }}" :active="request()->routeIs('*.contact.*')" />

    <div @click.stop class="py-3 text-center">
        <x-nav-language-switcher :locales="$locales" classAttributes="justify-center" />
    </div>
</div>
