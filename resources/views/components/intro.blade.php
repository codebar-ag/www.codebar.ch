@use(App\Helpers\HelperMarkdown;use Illuminate\Support\Str)

@php
    $locale = app()->getLocale();
    $localeCode = Str::before($locale, '_');

    $markdownContent = file_get_contents(database_path("files/intro/codebar_intro_{$localeCode}.md"));
    $htmlContent = $markdownContent ? app(HelperMarkdown::class)->formatMarkdown($markdownContent) : '';
@endphp

<x-layout.section>
    <x-h1 :title="__('Welcome')" />

    <x-ui.prose class="mt-4 max-w-none">
        {!! $htmlContent !!}
    </x-ui.prose>
</x-layout.section>
