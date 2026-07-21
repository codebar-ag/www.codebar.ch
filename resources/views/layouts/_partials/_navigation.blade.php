<nav class="mt-12 text-xl md:text-2xl px-4 md:px-0" x-data="navigation">
    <div class="flex justify-between items-center">

        <a href="{{ localized_route('start.index') }}" title="Start page" class="group inline-block max-w-1/2">
            @include('layouts._logos._codebar')
        </a>

        @if(!empty($locales))
            <div class="hidden lg:flex gap-2 text-lg items-center">
                @foreach($locales as $language)
                    <form method="POST" action="{{ route('language.update') }}">
                        @csrf
                        <input type="hidden" name="language" value="{{ $language->value }}">
                        <button type="submit" class="hover:text-brand hover:font-semibold transition cursor-pointer"
                                title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                            {{ $language->getLabel() }}
                        </button>
                    </form>
                    @if (!$loop->last)
                        <span class="text-gray-300 font-light">|</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-2 flex items-center justify-between">
        @include('layouts._partials._navigation_desktop')

        <button @click="toggle"
                class="flex items-center gap-1 lg:hidden hover:text-brand hover:font-semibold transition focus:outline-none">
            <span>{{ __('Menu') }}</span>

            <div class="transition-transform duration-300 ease-in-out" x-bind:class="icon_rotate">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                          d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        </button>
    </div>

    <div class="lg:hidden">
        @include('layouts._partials._navigation_mobile')
    </div>
</nav>
