@use(App\Enums\LocaleEnum;use Illuminate\Support\Str;use App\Helpers\HelperMarkdown;use Illuminate\Support\Arr)

@php
    $locale = app()->getLocale();
    $teamUrl = match ($locale) {
        LocaleEnum::EN->value => route(Str::slug(LocaleEnum::EN->value).'.about-us.index'),
        default => route(Str::slug(LocaleEnum::DE->value).'.about-us.index'),
    };
    $markdownContent = Arr::get($configuration?->component_intro, $locale);
    $htmlContent = $markdownContent ? app(HelperMarkdown::class)->formatMarkdown($markdownContent) : '';
@endphp

@if(filled($htmlContent))
    <x-ui.section>
        <x-ui.eyebrow text="{{ __('Welcome') }}" />
        <div class="mt-6 grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
            <h2 class="text-3xl md:text-4xl font-semibold tracking-tight text-zinc-950 text-balance">
                {{ __('Who we are, how we work.') }}
            </h2>
            <div class="prose prose-zinc max-w-none">
                {!! $htmlContent !!}
                <p class="not-prose mt-8">
                    <a
                        href="{{ $teamUrl }}"
                        class="inline-flex items-center gap-1.5 text-zinc-950 underline decoration-zinc-300 underline-offset-4 hover:decoration-zinc-950"
                    >
                        {{ __('components.intro.buttons.more') }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </p>
            </div>
        </div>
    </x-ui.section>
@endif
