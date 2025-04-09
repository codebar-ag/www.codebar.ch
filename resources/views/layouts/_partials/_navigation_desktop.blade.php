@php use App\Enums\LocaleEnum; @endphp

<div class="hidden md:flex justify-between w-full">

    {{-- Left-aligned primary navigation --}}
    <div class="flex gap-2">

        <x-a :href="localized_route('start.index')" label="{{ __('News') }}"
             classAttributes="text-xl md:text-2xl"/>


        {{--
             <span class="text-gray-500">|</span>

         <x-a :href="localized_route('about-us.index')" label="{{ __('About us') }}"
                     classAttributes="text-xl md:text-2xl"/>--}}

        <span class="text-gray-500">|</span>

        <x-a :href="localized_route('services.index')" label="{{ __('Services') }}"
             classAttributes="text-xl md:text-2xl"/>

        <span class="text-gray-500">|</span>

        <x-a :href="localized_route('products.index')" label="{{ __('Products') }}"
             classAttributes="text-xl md:text-2xl"/>

        <span class="text-gray-500">|</span>

        <x-a :href="localized_route('contact.index')" label="{{ __('Contact') }}"
             classAttributes="text-xl md:text-2xl"/>

    </div>

    <div class="flex gap-2 text-lg items-center">
        <a href="{{ route('locale.update',LocaleEnum::DE->value) }}"
           class="hover:text-black hover:font-semibold transition"
           title="{{ __('Update to german language') }}">
            {{ __('DE') }}
        </a>
        <span class="text-gray-400 font-light">|</span>
        <a href="{{ route('locale.update',LocaleEnum::EN->value) }}"
           class="hover:text-black hover:font-semibold transition"
           title="{{ __('Update to english language') }}">
            {{ __('EN') }}
        </a>
    </div>

</div>