@use(App\Helpers\HelperMarkdown;use Illuminate\Support\Str)

@php
    $locale = app()->getLocale();
    $localeCode = Str::before($locale, '_');

    $markdownContent = file_get_contents(database_path("files/intro/codebar_intro_{$localeCode}.md"));
    $htmlContent = $markdownContent ? app(HelperMarkdown::class)->formatMarkdown($markdownContent) : '';
    $htmlContent = preg_replace('/<h2>/', '<h2 class="mt-6 mb-2 text-xl md:text-2xl font-semibold">', $htmlContent);
    $htmlContent = preg_replace('/<p>/', '<p class="mb-4">', $htmlContent);
@endphp

<div class="mt-6">
    <x-section>
        <x-h1 :title="__('Welcome')" />

        <div class="mt-4 leading-relaxed">
            {!! $htmlContent !!}
        </div>
    </x-section>
</div>
