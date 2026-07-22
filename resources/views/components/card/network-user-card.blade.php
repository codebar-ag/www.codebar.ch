@props(['user', 'divided' => true])

@php
    use App\Support\CloudinaryUrl;
@endphp

<div class="{{ $divided ? 'border-t border-dashed border-gray-200 pt-3 mt-3' : '' }}">
    <div class="flex items-center gap-2 min-w-0">
        @if($user->avatar)
            <img src="{{ CloudinaryUrl::src($user->avatar, 64) }}"
                 srcset="{{ CloudinaryUrl::srcset($user->avatar, 64) }}"
                 sizes="32px"
                 alt="{{ $user->name }}" loading="lazy"
                 class="size-8 shrink-0 rounded-full object-cover">
        @else
            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-medium text-white">
                {{ $user->initials() }}
            </span>
        @endif
        <span class="min-w-0 flex-1 leading-tight">
            <span class="block truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $user->name }}</span>
            @if($user->role)
                <span class="block truncate whitespace-nowrap text-sm text-muted">{{ $user->role }}</span>
            @endif
        </span>

        @if($user->linkedin || $user->email || $user->phone)
            <span class="flex shrink-0 items-center gap-3">
                @if($user->linkedin)
                    <a href="{{ $user->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"
                       title="LinkedIn"
                       class="text-muted hover:text-gray-800">
                        <x-icon.linkedin/>
                    </a>
                @endif
                @if($user->email)
                    <a href="mailto:{{ $user->email }}" aria-label="E-Mail"
                       title="{{ $user->email }}"
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
