<footer class="my-12 md:my-20 bg-white text-lg min-h-[200px]">
    <div class="flex flex-col gap-8">

        <div class="hidden md:flex flex-col items-start gap-4 md:gap-18 md:flex-row md:items-start md:justify-start mx-auto md:mx-0">
            <div>
                <h2 class="text-black font-semibold">{{ config('site.company') }}</h2>
                <ul class="mt-1 list-none text-base">
                    <li>
                        <x-a :href="localized_route('about-us.index')" label="{{ __('About us') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('services.index')" label="{{ __('Services') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('products.index')" label="{{ __('Products') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('media.index')" label="{{ __('Media') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('jobs.index')" label="{{ __('Jobs') }}" classAttributes="text-lg"/>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="text-black font-semibold">{{ __('Legal') }}</h2>
                <ul class="mt-1 list-none text-base">
                    <li>
                        <x-a :href="localized_route('legal.terms.index')" label="{{ __('Terms') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('legal.privacy.index')" label="{{ __('Privacy') }}" classAttributes="text-lg"/>
                    </li>
                    <li>
                        <x-a :href="localized_route('legal.imprint.index')" label="{{ __('Imprint') }}" classAttributes="text-lg"/>
                    </li>
                </ul>
            </div>
        </div>

        <div>
            @include('layouts._partials._footer.labels')
        </div>

        <div class="text-base text-gray-500 text-center md:text-left">
            <span title="{{ app()->getLocale() }}">© {{ date('Y') }} {{ config('site.company') }}</span>
        </div>
    </div>
</footer>
