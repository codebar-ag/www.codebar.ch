<div class="hidden md:flex justify-between w-full">
    <div class="flex gap-2 items-center">

        <x-ui.link :href="localized_route('start.index')" label="{{ __('Home') }}" class="text-xl md:text-2xl" />

        <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('services.index')" label="{{ __('Services') }}" class="text-xl md:text-2xl" />

        <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('about-us.index')" label="{{ __('Team') }}" class="text-xl md:text-2xl" />

        {{-- <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('technologies.index')" label="{{ __('Technologies') }}" class="text-xl md:text-2xl" /> --}}

        <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('ai.index')" label="{{ __('AI') }}" class="text-xl md:text-2xl" />

        <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('network.index')" label="{{ __('Network') }}" class="text-xl md:text-2xl" />

        <span class="text-gray-300" aria-hidden="true">|</span>

        <x-ui.link :href="localized_route('contact.index')" label="{{ __('Contact') }}" class="text-xl md:text-2xl" />

    </div>
</div>
