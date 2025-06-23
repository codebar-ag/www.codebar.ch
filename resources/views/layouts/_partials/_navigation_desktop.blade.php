@php use App\Enums\LocaleEnum; @endphp

<div class="hidden md:flex justify-between w-full">

    {{-- Left-aligned primary navigation --}}
    <div class="flex gap-2">

        <x-a :href="localized_route('start.index')" label="{{ __('News') }}"
             classAttributes="text-xl md:text-2xl"/>

        <span class="text-gray-500">|</span>

        <x-a :href="localized_route('about-us.index')" label="{{ __('About us') }}"
             classAttributes="text-xl md:text-2xl"/>

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

    <!-- Language -->
    @if(!empty($locales))
        <div class="flex gap-2 text-lg items-center">
            @foreach($locales as $language)
                <form method="POST" action="{{ route('language.update') }}">
                    @csrf
                    <input type="hidden" name="language" value="{{ $language->value }}">
                    <button type="submit" class="hover:text-black hover:font-semibold transition cursor-pointer"
                            title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                        {{ $language->getLabel() }}
                    </button>
                </form>
                @if (!$loop->last)
                    <span class="text-gray-400 font-light">|</span>
                @endif
            @endforeach
        </div>
    @endif

</div>