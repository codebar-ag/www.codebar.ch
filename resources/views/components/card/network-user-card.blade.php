@props(['user', 'divided' => true])

@php
    use App\Support\CloudinaryUrl;
@endphp

<div class="{{ $divided ? 'mt-3 border-t border-dashed border-border pt-3' : '' }}">
    <div class="flex min-w-0 items-center gap-2">
        @if($user->avatar_url)
            <img src="{{ CloudinaryUrl::src($user->avatar_url, 64) }}"
                 srcset="{{ CloudinaryUrl::srcset($user->avatar_url, 64) }}"
                 sizes="32px"
                 alt="{{ $user->name }}" loading="lazy"
                 class="size-8 shrink-0 rounded-full object-cover">
        @else
            <img src="{{ $user->avatarDisplayUrl(64) }}"
                 alt="{{ $user->name }}" loading="lazy"
                 class="size-8 shrink-0 rounded-full bg-gray-100 object-cover">
        @endif

        <span class="min-w-0 flex-1 leading-tight">
            <span class="block truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $user->name }}</span>
            @if($user->role)
                <span class="block truncate whitespace-nowrap text-sm text-muted">{{ $user->role }}</span>
            @endif
        </span>

        <x-ui.social-links :name="$user->name" class="shrink-0 sm:gap-3" :links="[
            'linkedin' => $user->linkedin,
            'email' => $user->public_email,
            'phone' => $user->phone,
        ]"/>
    </div>
</div>
