@props(['user', 'divided' => true])

<div class="{{ $divided ? 'border-t border-dashed border-gray-200 pt-3 mt-3' : '' }}">
    <div class="flex items-center gap-2 min-w-0">
        @if($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" loading="lazy"
                 class="size-8 shrink-0 rounded-full object-cover">
        @else
            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-medium text-white">
                {{ $user->initials() }}
            </span>
        @endif
        <span class="min-w-0 leading-tight">
            <span class="block truncate whitespace-nowrap text-base font-bold text-gray-800">{{ $user->name }}</span>
            @if($user->role)
                <span class="block truncate whitespace-nowrap text-sm text-muted">{{ $user->role }}</span>
            @endif
        </span>
    </div>

    @if($user->linkedin || $user->email || $user->phone)
        <div class="mt-2 flex flex-wrap gap-1.5">
            @if($user->linkedin)
                <a href="{{ $user->linkedin }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center whitespace-nowrap rounded-pill bg-gray-400/10 px-2.5 py-0.5 text-xs font-medium text-muted ring-1 ring-gray-400/20 ring-inset transition hover:text-brand">
                    LinkedIn
                </a>
            @endif
            @if($user->email)
                <a href="mailto:{{ $user->email }}"
                   class="inline-flex items-center whitespace-nowrap rounded-pill bg-gray-400/10 px-2.5 py-0.5 text-xs font-medium text-muted ring-1 ring-gray-400/20 ring-inset transition hover:text-brand">
                    {{ __('Email') }}
                </a>
            @endif
            @if($user->phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $user->phone) }}"
                   class="inline-flex items-center whitespace-nowrap rounded-pill bg-gray-400/10 px-2.5 py-0.5 text-xs font-medium text-muted ring-1 ring-gray-400/20 ring-inset transition hover:text-brand">
                    {{ __('Phone') }}
                </a>
            @endif
        </div>
    @endif
</div>
