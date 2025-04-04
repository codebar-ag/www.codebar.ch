<div class="hidden md:flex justify-between w-full">

    {{-- Left-aligned primary navigation --}}
    <div class="flex gap-2">
        <a href="{{ route('news.index') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('News') }}">
            {{ __('News') }}
        </a>

        <span class="text-gray-500">|</span>
        <a href="{{ route('about-us.index') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('About us') }}">
            {{ __('About us') }}
        </a>
        <span class="text-gray-500">|</span>

        <a href="{{ route('services.index') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('Services') }}">
            {{ __('Services') }}
        </a>

        <span class="text-gray-500">|</span>
        <a href="{{ route('products.index') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('Products') }}">
            {{ __('Products') }}
        </a>

        <span class="text-gray-500">|</span>
        <a href="{{ route('contact.index') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('Contact') }}">
            {{ __('Contact') }}
        </a>
    </div>

   {{-- <div class="flex gap-2 text-lg items-center">
        <a href="{{ route('locale.update','de') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('Update to german language') }}">
            {{ __('Deutsch') }}
        </a>
        <span class="text-gray-400 font-light">|</span>
        <a href="{{ route('locale.update','en') }}" class="hover:text-black hover:font-semibold transition"
           title="{{ __('Update to english language') }}">
            {{ __('English') }}
        </a>
    </div>--}}

</div>