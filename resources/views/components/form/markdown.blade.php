@props([
    'name',
    'value' => null,
    'rows' => 8,
    'placeholder' => null,
])

@php
    $hasError = $errors->has($name);
    $button = 'grid size-8 shrink-0 cursor-pointer place-items-center rounded-md text-gray-600 transition hover:bg-white hover:text-brand focus-ring';
@endphp

<div x-data="markdownEditor" x-on:keydown.escape.window="closePreview">
    <div class="flex items-center gap-1 rounded-t-panel border border-b-0 border-border-strong bg-surface px-2 py-1.5"
         role="toolbar" aria-label="{{ __('Formatting') }}">
        <button type="button" x-on:click="bold" title="{{ __('Bold') }}" aria-label="{{ __('Bold') }}" class="{{ $button }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                <path d="M7 5h6a3.5 3.5 0 0 1 0 7H7zM7 12h7a3.5 3.5 0 0 1 0 7H7z"/>
            </svg>
        </button>
        <button type="button" x-on:click="italic" title="{{ __('Italic') }}" aria-label="{{ __('Italic') }}" class="{{ $button }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="size-4" aria-hidden="true">
                <path d="M11 5h6M7 19h6M14 5l-4 14"/>
            </svg>
        </button>

        <span class="mx-1 h-5 w-px bg-border-strong" aria-hidden="true"></span>

        <button type="button" x-on:click="bulletList" title="{{ __('List') }}" aria-label="{{ __('List') }}" class="{{ $button }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="size-4" aria-hidden="true">
                <path d="M9 6h11M9 12h11M9 18h11"/>
                <circle cx="4.5" cy="6" r="1.2" fill="currentColor" stroke="none"/>
                <circle cx="4.5" cy="12" r="1.2" fill="currentColor" stroke="none"/>
                <circle cx="4.5" cy="18" r="1.2" fill="currentColor" stroke="none"/>
            </svg>
        </button>
        <button type="button" x-on:click="orderedList" title="{{ __('Numbered list') }}" aria-label="{{ __('Numbered list') }}" class="{{ $button }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="size-4" aria-hidden="true">
                <path d="M10 6h10M10 12h10M10 18h10"/>
                <path d="M4 5.5 5.5 4.5V9M4 13.5h3l-3 3.5h3" stroke-width="1.6"/>
            </svg>
        </button>

        <span class="mx-1 h-5 w-px bg-border-strong" aria-hidden="true"></span>

        <button type="button" x-on:click="link" title="{{ __('Link') }}" aria-label="{{ __('Link') }}" class="{{ $button }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4" aria-hidden="true">
                <path d="M10 14a5 5 0 0 0 7.07 0l2.12-2.12a5 5 0 0 0-7.07-7.07l-1.4 1.4"/>
                <path d="M14 10a5 5 0 0 0-7.07 0l-2.12 2.12a5 5 0 0 0 7.07 7.07l1.4-1.4"/>
            </svg>
        </button>

        <button type="button" x-on:click="openPreview"
                class="ml-auto rounded-pill px-2 py-1 text-sm font-medium text-gray-600 transition hover:text-brand focus-ring">
            {{ __('Preview') }}
        </button>
    </div>

    <textarea id="{{ $name }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              x-ref="input"
              @if(filled($placeholder)) placeholder="{{ $placeholder }}" @endif
              @if($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
              class="block w-full rounded-b-panel border border-border-strong bg-white px-4 py-3 text-base text-gray-800 placeholder-hint transition focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand aria-invalid:border-red-600 aria-invalid:focus:border-red-600 aria-invalid:focus:ring-red-600">{{ old($name, $value) }}</textarea>

    <div x-show="previewOpen" x-cloak x-transition.opacity x-trap.inert.noscroll="previewOpen"
         role="dialog" aria-modal="true" aria-labelledby="{{ $name }}-preview-title"
         class="fixed inset-0 z-50 overflow-y-auto bg-white"
         x-on:click.self="closePreview">
        <div class="sticky top-0 flex items-center justify-between gap-4 border-b border-border bg-white/95 px-6 py-4 backdrop-blur"
             x-on:click.self="closePreview">
            <p id="{{ $name }}-preview-title" class="text-subheading font-semibold text-gray-900">{{ __('Preview') }}</p>
            <button type="button" x-on:click="closePreview" aria-label="{{ __('Close') }}"
                    class="grid size-10 place-items-center rounded-pill text-gray-600 transition hover:bg-surface hover:text-brand focus-ring">
                <x-icon.close class="size-6"/>
            </button>
        </div>
        <div class="px-6 py-10" x-on:click.self="closePreview">
            <div class="mx-auto max-w-3xl">
                <p x-show="isEmpty" class="text-base text-hint">{{ __('Nothing to preview yet') }}</p>
                <div x-show="hasPreview" x-html="preview"
                     class="prose prose-gray max-w-none md:prose-lg prose-p:my-3 prose-ul:my-3 prose-ol:my-3 prose-li:my-1"></div>
            </div>
        </div>
    </div>
</div>
