<div class="lg:hidden">
    <div
        x-cloak
        x-show="openDrawer"
        x-transition.opacity.duration.180ms
        class="fixed inset-0 z-50"
        aria-hidden="true"
    >
        <button
            type="button"
            class="absolute inset-0 bg-slate-900/35 backdrop-blur-[1px]"
            @click="closeDrawer()"
            aria-label="{{ __('Close navigation overlay') }}"
        ></button>

        <aside
            id="mobile-main-nav"
            class="nav-mobile-drawer menu-shell surface-card"
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('Main menu') }}"
        >
            <header class="flex items-center justify-between border-b border-(--border-subtle) px-5 py-4">
                <p class="text-sm font-semibold tracking-wide text-(--text-soft)">{{ __('Navigation') }}</p>
                <button
                    type="button"
                    class="nav-trigger"
                    @click="closeDrawer()"
                    aria-label="{{ __('Close menu') }}"
                >
                    {{ __('Close') }}
                </button>
            </header>

            <div class="nav-drawer-scroll">
                @foreach($navBuckets as $bucket)
                    @if(($bucket['type'] ?? null) === 'link')
                        <a href="{{ $bucket['href'] }}"
                           title="{{ $bucket['label'] }}"
                           class="nav-mobile-link"
                           @click="closeDrawer()">
                            {{ $bucket['label'] }}
                        </a>
                    @else
                        <section class="nav-mobile-section">
                            <button
                                type="button"
                                class="nav-mobile-section-trigger"
                                @click="toggleSection('{{ $bucket['key'] }}')"
                                x-bind:aria-expanded="isSectionOpen('{{ $bucket['key'] }}').toString()"
                                aria-controls="drawer-section-{{ $bucket['key'] }}"
                            >
                                <span>{{ $bucket['label'] }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 transition-transform duration-200"
                                     x-bind:class="{ 'rotate-180': isSectionOpen('{{ $bucket['key'] }}') }">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div
                                id="drawer-section-{{ $bucket['key'] }}"
                                class="space-y-1"
                                x-cloak
                                x-show="isSectionOpen('{{ $bucket['key'] }}')"
                                x-transition.duration.160ms
                            >
                                @foreach(($bucket['items'] ?? []) as $item)
                                    <a href="{{ $item['href'] }}"
                                       title="{{ $item['label'] }}"
                                       class="nav-mobile-sub-link"
                                       @click="closeDrawer()">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>

            <footer class="space-y-4 border-t border-(--border-subtle) px-5 py-4">
                @if(!empty($locales))
                    <div class="flex items-center gap-2">
                        @foreach($locales as $language)
                            <form method="POST" action="{{ route('language.update') }}">
                                @csrf
                                <input type="hidden" name="language" value="{{ $language->value }}">
                                <button type="submit" class="brand-link rounded px-2.5 py-1.5 text-sm">
                                    {{ $language->getLabel() }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <div class="text-sm text-(--text-soft)">
                    <a href="tel:0041615156090" class="brand-link mr-4">+41 61 515 60 90</a>
                    <a href="mailto:info@paperflakes.ch" class="brand-link">info@paperflakes.ch</a>
                </div>
            </footer>
        </aside>
    </div>
</div>
