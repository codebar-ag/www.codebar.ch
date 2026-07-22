<footer class="my-12 md:my-20 bg-white text-lg min-h-[200px]">
    <div class="flex flex-col gap-8">

        <nav aria-label="{{ __('Footer') }}"
             class="flex flex-wrap items-start gap-x-18 gap-y-4">
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ __('Legal') }}</h2>
                <ul class="mt-1 list-none text-base">
                    <li>
                        <x-ui.link :href="localized_route('legal.privacy.index')" label="{{ __('Privacy') }}"
                                   class="text-lg"/>
                    </li>
                    <li>
                        <x-ui.link :href="localized_route('legal.imprint.index')" label="{{ __('Imprint') }}"
                                   class="text-lg"/>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ __('Resources') }}</h2>
                <ul class="mt-1 list-none text-base">
                    <li>
                        <x-ui.link :href="localized_route('media.index')" label="{{ __('Media') }}"
                                   class="text-lg"/>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ __('Network') }}</h2>
                <ul class="mt-1 list-none text-base">
                    <li>
                        <x-ui.link :href="localized_route('network.request.index')" label="{{ __('Manage my profile') }}"
                                   class="text-lg"/>
                    </li>
                </ul>
            </div>
        </nav>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        <div class="text-base text-gray-500 text-center md:text-left">
            <span title="{{ app()->getLocale() }}">© {{ date('Y') }} codebar Solutions AG</span>
        </div>
    </div>
</footer>
