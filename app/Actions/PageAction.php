<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTO\PageDTO;
use App\Enums\LocaleEnum;
use App\Models\Network;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use App\Support\LocalizedRouteParameters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PageAction
{
    private string $locale;

    /**
     * @param  Collection<int, PageDTO>|null  $referencePages
     */
    public function __construct(
        ?string $locale = null,
        private ?string $routeName = null,
        private mixed $routeParameters = [],
        private ?Collection $referencePages = null,
    ) {
        $this->locale = $locale ?? app()->getLocale();
    }

    public function default(): ?PageDTO
    {
        $page = $this->findPage();

        if (! $page) {
            return null;
        }

        return new PageDTO(
            locale: $this->locale,
            routeKey: $page->key,
            routeName: Str::slug($this->locale).'.'.$this->routeName,
            title: $this->translatedString($page->getTranslation('title', $this->locale)),
            robots: $page->robots,
            description: $this->translatedString($page->getTranslation('description', $this->locale)),
            image: $page->image,
            lastModificationDate: Carbon::parse($page->updated_at ?? now()),
            routeParameters: $this->routeParameters,
            referencePages: $this->referencePages
        );
    }

    public function news(News $news, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'news.show',
            routeName: Str::slug(title: $locale).'.news.show',
            title: $this->translatedString($news->getTranslation('title', $locale)),
            description: $this->translatedString($news->getTranslation('teaser', $locale)),
            image: $news->hero_image,
            lastModificationDate: Carbon::parse($news->revised_at ?? $news->published_at ?? $news->updated_at ?? now()),
            routeParameters: LocalizedRouteParameters::for(['locale' => $locale, 'news' => $news], $locale),
            referencePages: $withReferences ? $this->alternateLocalePages($news, $locale, fn (News $n, string $l) => $this->news($n, false, $l)) : null,
            publishedAt: $news->published_at !== null ? Carbon::parse($news->published_at) : null,
            authorName: $news->authorName(),
        );
    }

    public function network(Network $network, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'network.show',
            routeName: Str::slug(title: $locale).'.network.show',
            title: $this->translatedString($network->getTranslation('name', $locale)),
            description: $this->nullableTranslatedString($network->getTranslation('excerpt', $locale)),
            image: $network->cover_url,
            lastModificationDate: Carbon::parse($network->updated_at ?? now()),
            routeParameters: ['slug' => $network->page_slug],
            referencePages: null,
        );
    }

    public function product(Product $product, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'products.show',
            routeName: Str::slug(title: $locale).'.products.show',
            title: $this->translatedString($product->getTranslation('name', $locale)),
            description: $this->translatedString($product->getTranslation('teaser', $locale)),
            image: $product->image,
            lastModificationDate: Carbon::parse($product->updated_at ?? now()),
            routeParameters: ['locale' => $locale, 'product' => $product],
            referencePages: $withReferences ? $this->alternateLocalePages($product, $locale, fn (Product $p, string $l) => $this->product($p, false, $l)) : null,
        );
    }

    public function service(Service $service, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'services.show',
            routeName: Str::slug(title: $locale).'.services.show',
            title: $this->translatedString($service->getTranslation('name', $locale)),
            description: $this->translatedString($service->getTranslation('teaser', $locale)),
            image: $service->image,
            lastModificationDate: Carbon::parse($service->updated_at ?? now()),
            routeParameters: ['locale' => $locale, 'service' => $service],
            referencePages: $withReferences ? $this->alternateLocalePages($service, $locale, fn (Service $s, string $l) => $this->service($s, false, $l)) : null,
        );
    }

    public function technology(Technology $technology, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'technologies.show',
            routeName: Str::slug(title: $locale).'.technologies.show',
            title: $this->translatedString($technology->getTranslation('title', $locale)),
            description: $this->translatedString($technology->getTranslation('teaser', $locale)),
            image: $technology->image,
            lastModificationDate: Carbon::parse($technology->updated_at ?? now()),
            routeParameters: ['locale' => $locale, 'technology' => $technology],
            referencePages: $withReferences ? $this->alternateLocalePages($technology, $locale, fn (Technology $t, string $l) => $this->technology($t, false, $l)) : null,
        );
    }

    public function openSource(OpenSource $openSource, bool $withReferences = false, ?string $locale = null): PageDTO
    {
        $locale ??= $this->locale;

        return new PageDTO(
            locale: $locale,
            routeKey: 'open-source.show',
            routeName: Str::slug(title: $locale).'.open-source.show',
            title: $this->translatedString($openSource->getTranslation('title', $locale)),
            description: $this->translatedString($openSource->getTranslation('teaser', $locale)),
            image: $openSource->image,
            lastModificationDate: Carbon::parse($openSource->updated_at ?? now()),
            routeParameters: ['locale' => $locale, 'openSource' => $openSource],
            referencePages: $withReferences ? $this->alternateLocalePages($openSource, $locale, fn (OpenSource $o, string $l) => $this->openSource($o, false, $l)) : null,
        );
    }

    /**
     * The same model, rendered as a PageDTO for every other configured locale —
     * used to build hreflang alternate-language links.
     *
     * @template T of Model
     *
     * @param  T  $model
     * @param  callable(T, string): PageDTO  $builder
     * @return Collection<int, PageDTO>
     */
    private function alternateLocalePages(Model $model, string $locale, callable $builder): Collection
    {
        return collect(LocaleEnum::cases())
            ->reject(fn (LocaleEnum $case): bool => $case->value === $locale)
            ->map(fn (LocaleEnum $case): PageDTO => $builder($model, $case->value))
            ->values();
    }

    private function translatedString(mixed $value): string
    {
        return is_string($value) ? $value : '';
    }

    private function nullableTranslatedString(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }

    private function findPage(): ?Page
    {
        return Page::where('key', $this->routeName)->first();
    }
}
