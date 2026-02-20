<x-app-layout :page="$page">

    <x-h1 :title="$name"/>
    @if($teaser)
        <x-h1-teaser :teaser="$teaser"/>
    @endif

    <x-section>
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
            @if($primaryLanguage)
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                    {{ $primaryLanguage }}
                </span>
            @endif
            @if($stars)
                <span class="inline-flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    {{ number_format($stars) }}
                </span>
            @endif
            @if($forks)
                <span class="inline-flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    {{ number_format($forks) }}
                </span>
            @endif
        </div>

        @php
            $tagList = collect($tags);
        @endphp

        @if($tagList->count())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($tagList as $tag)
                    <x-badge label="{{ $tag }}" class-attributes="text-xs"/>
                @endforeach
            </div>
        @endif
    </x-section>

    @if($content)
        <x-section>
            <x-content :content="$content"/>
        </x-section>
    @endif

    @if($githubUrl)
        <x-section>
            <a href="{{ $githubUrl }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 rounded-md bg-gray-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-black">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                {{ __('View on GitHub') }}
            </a>
        </x-section>
    @endif

</x-app-layout>
