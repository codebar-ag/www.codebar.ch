@props(['user', 'divided' => true])

@php
    use App\Support\CloudinaryUrl;
@endphp

<div class="{{ $divided ? 'border-t border-dashed border-gray-200 pt-3 mt-3' : '' }}">
    <div class="flex items-center gap-2 min-w-0">
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

        @if($user->linkedin || $user->public_email || $user->phone)
            <span class="flex shrink-0 items-center gap-3">
                @if($user->linkedin)
                    <a href="{{ $user->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                       title="LinkedIn"
                       class="text-muted hover:text-gray-800">
                        <x-icon.linkedin/>
                    </a>
                @endif
                @if($user->public_email)
                    <a href="mailto:{{ $user->public_email }}" aria-label="E-Mail"
                       title="{{ $user->public_email }}"
                       class="text-muted hover:text-gray-800">
                        <x-icon.email/>
                    </a>
                @endif
                @if($user->phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $user->phone) }}" aria-label="{{ __('Phone') }}"
                       title="{{ $user->phone }}"
                       class="text-muted hover:text-gray-800">
                        <x-icon.phone/>
                    </a>
                @endif
            </span>
        @endif
    </div>
</div>
