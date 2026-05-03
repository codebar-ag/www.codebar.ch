<div class="relative mt-4 hidden lg:block" @mouseleave="closePanel()">
    <div class="nav-desktop-bar">
        @foreach($navBuckets as $bucket)
            @if(($bucket['type'] ?? null) === 'link')
                <a href="{{ $bucket['href'] }}"
                   title="{{ $bucket['label'] }}"
                   class="nav-link nav-trigger">
                    {{ $bucket['label'] }}
                </a>
            @else
                <button
                    type="button"
                    class="nav-trigger"
                    x-bind:class="{ 'is-active': isPanel('{{ $bucket['key'] }}') }"
                    @mouseenter="openPanel('{{ $bucket['key'] }}')"
                    @focus="openPanel('{{ $bucket['key'] }}')"
                    @click="togglePanel('{{ $bucket['key'] }}')"
                    x-bind:aria-expanded="isPanel('{{ $bucket['key'] }}').toString()"
                    aria-haspopup="true"
                    aria-controls="mega-panel-{{ $bucket['key'] }}"
                >
                    <span>{{ $bucket['label'] }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform duration-200"
                         x-bind:class="{ 'rotate-180': isPanel('{{ $bucket['key'] }}') }">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            @endif
        @endforeach
    </div>

    @foreach($navBuckets as $bucket)
        @if(($bucket['type'] ?? null) === 'panel')
            <section
                id="mega-panel-{{ $bucket['key'] }}"
                class="nav-mega-panel"
                x-cloak
                x-show="isPanel('{{ $bucket['key'] }}')"
                x-transition.origin.top.left.duration.180ms
                @mouseenter="openPanel('{{ $bucket['key'] }}')"
            >
                <div class="nav-mega-grid">
                    <div class="space-y-2">
                        <p class="editorial-kicker">{{ $bucket['label'] }}</p>
                        <h3 class="text-2xl font-semibold leading-tight">{{ $bucket['label'] }}</h3>
                        <p class="text-sm text-(--text-soft)">
                            {{ $bucket['description'] ?? '' }}
                        </p>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach(($bucket['items'] ?? []) as $item)
                            <a href="{{ $item['href'] }}"
                               title="{{ $item['label'] }}"
                               class="nav-mega-item">
                                <span class="font-medium">{{ $item['label'] }}</span>
                                <span aria-hidden="true">→</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endforeach
</div>
