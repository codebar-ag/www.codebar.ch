@use(App\Enums\LocaleEnum;use Illuminate\Support\Facades\Config;use Illuminate\Support\Str;use App\Helpers\HelperMarkdown;use Illuminate\Support\Arr)

@php
    $locale = app()->getLocale();
    $color = $configuration?->company_primary_color;

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
    <x-h1 :title="__('Welcome')" />
    <x-section class-attributes="relative isolate bg-gray-100 overflow-hidden">
        <div class="absolute -top-32 -left-20 -z-10 h-[30rem] w-[30rem] rounded-full opacity-10 blur-[120px]"
            style="background-color: {{ $color }};">
        </div>
        <section class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10 p-6 md:p-12">
            <div class="w-full">
                <div class="text-gray-700 text-base md:text-lg mb-6">
                    {!! $htmlContent !!}
                </div>
                <div class="flex flex-col sm:flex-row gap-2 text-center">
                    <a href="{{ $team_url }}" rel="noopener noreferrer"
                        class="px-4 py-2 border rounded-md text-sm font-medium hover:font-semibold transition w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-offset-2 text-gray-800"
                        style="background-color: white; border-color: {{ $color }}; --tw-ring-color: {{ $color }};">
                        {{ __('components.intro.buttons.more') }}
                    </a>
                </div>
            </div>
        </section>
    </x-section>
</div>
