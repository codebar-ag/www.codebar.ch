@props(['image', 'imageContainerClassAttributes' => null, 'name', 'role' => null, 'icons' => []])

@php
    use App\Support\CloudinaryUrl;
@endphp

<div class="group flex flex-row overflow-hidden rounded-panel transition">
    <div class="{{ $imageContainerClassAttributes ?? 'size-32 shrink-0' }} overflow-hidden rounded-panel">
        <img src="{{ CloudinaryUrl::src($image, 256) }}"
             srcset="{{ CloudinaryUrl::srcset($image, 256) }}"
             sizes="128px"
             width="128"
             height="128"
             alt="{{ $name }}"
             loading="lazy"
             class="size-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105"/>
    </div>

    <div class="flex min-w-0 flex-col justify-center p-4">
        <div class="text-base leading-tight font-bold text-gray-800">{{ $name }}</div>

        @if(filled($role))
            <div class="mt-1 text-sm leading-snug text-muted">{{ $role }}</div>
        @endif

        <x-ui.social-links :links="$icons" :name="$name" class="mt-1 -ml-2.5 sm:mt-2 sm:ml-0 sm:gap-3"/>
    </div>
</div>
