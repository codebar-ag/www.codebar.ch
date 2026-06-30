@use(App\Helpers\HelperMarkdown;use Illuminate\Support\Arr)

@php
    $locale = app()->getLocale();

    $markdownContent = Arr::get($configuration?->component_intro, $locale);
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
