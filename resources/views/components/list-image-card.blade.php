@props([
    'image',
    'name',
    'role' => null,
    'icons' => [],
])

<div class="flex rounded-xl overflow-hidden transition hover:bg-gray-50/50 hover:shadow-sm group">
    <div class="h-28 w-28 flex-shrink-0 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $name }}"
             class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105"/>
    </div>

    <div class="flex flex-col justify-center p-4 space-y-1">
        <div class="text-base font-bold text-gray-900 leading-tight">
            {{ $name }}
        </div>

        @if(!blank($role))
            <div class="text-sm text-gray-500 leading-snug">
                {{ $role }}
            </div>
        @endif

        @php $icons = collect($icons); @endphp

        @if($icons->isNotEmpty())
            <div class="flex gap-3 pt-2">
                @foreach($icons as $type => $url)
                    @switch($type)
                        @case('linkedin')
                            <a href="{{ $url }}" target="_blank" title="LinkedIn" aria-label="LinkedIn"
                               class="text-gray-500 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                                    <rect width="4" height="12" x="2" y="9"/>
                                    <circle cx="4" cy="4" r="2"/>
                                </svg>
                            </a>
                            @break

                        @case('github')
                            <a href="{{ $url }}" target="_blank" title="GitHub" aria-label="GitHub"
                               class="text-gray-500 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                                    <path d="M9 18c-4.51 2-5-2-7-2"/>
                                </svg>
                            </a>
                            @break

                        @case('email')
                            <a href="{{ $url }}" target="_blank" title="E-Mail" aria-label="E-Mail"
                               class="text-gray-500 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/>
                                    <path d="m21.854 2.147-10.94 10.939"/>
                                </svg>
                            </a>
                            @break

                        @case('website')
                            <a href="{{ $url }}" target="_blank" title="Website" aria-label="Website"
                               class="text-gray-500 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                     stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                </svg>
                            </a>
                            @break
                    @endswitch
                @endforeach
            </div>
        @endif
    </div>
</div>