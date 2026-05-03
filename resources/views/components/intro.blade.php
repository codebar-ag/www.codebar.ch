@use(App\Enums\LocaleEnum;use Illuminate\Support\Str;use App\Helpers\HelperMarkdown;use Illuminate\Support\Arr)

@php
    $locale = app()->getLocale();
    $team_url = match ($locale) {
        LocaleEnum::EN->value => route(Str::slug(LocaleEnum::EN->value) . '.about-us.index'),
        default => route(Str::slug(LocaleEnum::DE->value) . '.about-us.index'),
    };
    $markdownContent = Arr::get($configuration?->component_intro, $locale);
    $htmlContent = $markdownContent ? app(HelperMarkdown::class)->formatMarkdown($markdownContent) : '';
    $htmlContent = preg_replace('/<h2>/', '<h2 class="mb-2 text-lg md:text-xl font-semibold">', $htmlContent);
    $htmlContent = preg_replace('/<p>/', '<p class="mb-4">', $htmlContent);
@endphp

<div class="mt-6">
    <x-ui.hero :title="__('Welcome')">
        <div class="prose prose-gray max-w-3xl">
            {!! $htmlContent !!}
        </div>
        <x-ui.button
            :href="$team_url"
            :label="__('components.intro.buttons.more')"
            variant="outline"
        />
    </x-ui.hero>
</div>
