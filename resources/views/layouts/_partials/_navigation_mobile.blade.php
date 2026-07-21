@php use App\Enums\LocaleEnum; @endphp

<div x-show="open" x-transition x-cloak class="mt-4 text-xl space-y-2">
    <!-- Home -->
    <a @click.stop href="{{ localized_route('start.index') }}" title="{{ __('Home') }}"
        class="block py-3 text-center bg-zinc-50/50 hover:text-brand hover:font-semibold transition rounded-t-lg">
        {{ __('Home') }}
    </a>

    <!-- Team -->
    <a @click.stop href="{{ localized_route('about-us.index') }}" title="{{ __('Team') }}"
        class="block py-3 text-center bg-zinc-50 hover:text-brand hover:font-semibold transition">
        {{ __('Team') }}
    </a>

    <!-- AI -->
    <a @click.stop href="{{ localized_route('ai.index') }}" title="{{ __('AI') }}"
        class="block py-3 text-center bg-zinc-50/50 hover:text-brand hover:font-semibold transition">
        {{ __('AI') }}
    </a>

    <!-- Contact -->
    <div @click.stop class="py-3 text-center bg-zinc-50/25 transition space-y-1">
        <a @click.stop href="{{ localized_route('contact.index') }}" title="{{ __('Contact') }}"
            class="block text-center bg-zinc-50/50 hover:text-brand hover:font-semibold transition rounded-t-lg">
            {{ __('Contact') }}
        </a>
        <div class="mt-1 text-sm text-gray-600 space-y-1">
            <a href="tel:0041615156090" title="{{ __('Contact Phone number') }}"
                class="block text-base hover:text-brand hover:font-semibold transition">
                +41 61 515 60 90
            </a>
            <a href="mailto:info@codebar.ch" title="{{ __('Contact email address') }}"
                class="block text-base hover:text-brand hover:font-semibold transition">
                info@codebar.ch
            </a>
        </div>
    </div>

    <!-- Language -->
    @if (!empty($locales))
        <div @click.stop class="py-3 text-center bg-zinc-50/50 transition space-y-1">
            <span>{{ __('Language') }}</span>
            <div class="mt-1 flex justify-center gap-4 text-sm text-gray-600">
                @foreach ($locales as $language)
                    <form method="POST" action="{{ route('language.update') }}" rel="nofollow">
                        @csrf
                        <input type="hidden" name="language" value="{{ $language->value }}">
                        <button type="submit"
                            class="text-base hover:text-brand hover:font-semibold transition cursor-pointer"
                            title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                            {{ $language->getLabel() }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif

</div>
