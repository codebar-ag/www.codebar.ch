@props(['image', 'imageContainerClassAttributes' => null, 'name', 'role' => null, 'icons' => []])

@php
    use App\Support\CloudinaryUrl;
@endphp

<div class="flex flex-row rounded-panel overflow-hidden transition group">
    <div class="{{ $imageContainerClassAttributes ?? 'h-32 w-32 flex-shrink-0 overflow-hidden' }}">
        <img src="{{ CloudinaryUrl::src($image, 256) }}"
            srcset="{{ CloudinaryUrl::srcset($image, 256) }}"
            sizes="128px"
            width="128"
            height="128"
            alt="{{ $name }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105" />
    </div>

    <div class="flex flex-col justify-center p-4 space-y-1">
        <div class="text-base font-bold text-gray-800 leading-tight">
            {{ $name }}
        </div>

        @if (!blank($role))
            <div class="text-sm text-muted leading-snug">
                {{ $role }}
            </div>
        @endif

        @php $icons = collect($icons); @endphp

        @if ($icons->isNotEmpty())
            <div class="flex gap-3 pt-2">
                @foreach ($icons as $type => $url)
                    @if (filled($url))
                        @switch($type)
                            @case('linkedin')
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                                    class="text-muted hover:text-gray-800">
                                    <x-icon.linkedin/>
                                </a>
                            @break

                            @case('github')
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub"
                                    class="text-muted hover:text-gray-800">
                                    <x-icon.github/>
                                </a>
                            @break

                            @case('email')
                                <a href="mailto:{{ $url }}" aria-label="E-Mail"
                                    class="text-muted hover:text-gray-800">
                                    <x-icon.email/>
                                </a>
                            @break

                            @case('website')
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Website"
                                    class="text-muted hover:text-gray-800">
                                    <x-icon.website/>
                                </a>
                            @break
                        @endswitch
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
