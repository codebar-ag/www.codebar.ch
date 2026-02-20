@props(['locales', 'classAttributes' => ''])

@if(!empty($locales))
    <div class="flex gap-2 text-lg items-center {{ $classAttributes }}">
        @foreach($locales as $language)
            <form method="POST" action="{{ route('language.update') }}">
                @csrf
                <input type="hidden" name="language" value="{{ $language->value }}">
                <button type="submit"
                        class="hover:text-black hover:font-semibold transition cursor-pointer"
                        title="{{ __('Update to :lang language', ['lang' => $language->getLabel()]) }}">
                    {{ $language->getLabel() }}
                </button>
            </form>
            @if(!$loop->last)
                <span class="text-gray-400 font-light">|</span>
            @endif
        @endforeach
    </div>
@endif
