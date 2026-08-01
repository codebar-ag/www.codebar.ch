<?php

declare(strict_types=1);

namespace App\Seo;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Support\NewsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

/**
 * Builds the schema.org JSON-LD graph emitted in <head>.
 *
 * Everything ends up in a single @graph so the nodes can reference each other
 * by @id instead of repeating themselves — the Organization is described once
 * and every page points at it. That is what lets Google and LLMs treat all our
 * pages as belonging to one known entity rather than as unrelated documents.
 *
 * Page-specific nodes (LocalBusiness, BlogPosting, Person, …) are appended by
 * the view through the `schema` prop on <x-app-layout>.
 *
 * @phpstan-type SchemaNode array<string, mixed>
 */
class SchemaGraph
{
    /**
     * Stable @id anchors. Fragments, not real URLs — schema.org identifiers
     * only have to be unique and stable, and keeping them tied to the site
     * root means they stay valid on every page.
     */
    public const string ORGANIZATION_ID = '#organization';

    public const string WEBSITE_ID = '#website';

    /**
     * @param  array<int, array<string, mixed>>  $additionalNodes
     */
    public function __construct(
        private readonly ?PageDTO $page = null,
        private readonly array $additionalNodes = [],
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $nodes = collect([
            $this->organization(),
            $this->website(),
        ])
            ->push(...$this->webPageNodes())
            ->push(...$this->additionalNodes)
            ->filter()
            ->values()
            ->all();

        return [
            '@context' => 'https://schema.org',
            '@graph' => $nodes,
        ];
    }

    public function toJson(): string
    {
        return (string) json_encode(
            $this->toArray(),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * The company itself. This is the node that feeds the Knowledge Panel on
     * branded searches, so it carries every identifier we can verify.
     *
     * @return array<string, mixed>
     */
    public function organization(): array
    {
        return array_filter([
            '@type' => 'Organization',
            '@id' => $this->id(self::ORGANIZATION_ID),
            'name' => Company::legalName(),
            'alternateName' => Company::alternateNames(),
            'url' => $this->baseUrl(),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $this->asset(Company::logo()),
            ],
            'image' => $this->asset(config()->string('seo.default_image')),
            'email' => Company::email(),
            'telephone' => Company::phone(),
            'vatID' => Company::uid(),
            'address' => $this->postalAddress(Company::primaryLocation()),
            'areaServed' => 'CH',
            'knowsAbout' => Company::knowsAbout(),
            'numberOfEmployees' => [
                '@type' => 'QuantitativeValue',
                'value' => Company::numberOfEmployees(),
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => Company::email(),
                'telephone' => Company::phone(),
                'areaServed' => 'CH',
                'availableLanguage' => ['de', 'en'],
            ],
            'sameAs' => Company::sameAs(),
        ], fn (mixed $value): bool => $value !== null && $value !== [] && $value !== '');
    }

    /**
     * @return array<string, mixed>
     */
    private function website(): array
    {
        return array_filter([
            '@type' => 'WebSite',
            '@id' => $this->id(self::WEBSITE_ID),
            'name' => Company::legalName(),
            'alternateName' => Company::alternateNames(),
            'url' => $this->baseUrl(),
            'inLanguage' => $this->language(),
            'publisher' => ['@id' => $this->id(self::ORGANIZATION_ID)],
            // alternateName is the only value here that can be empty.
        ], fn (mixed $value): bool => $value !== []);
    }

    /**
     * WebPage plus the breadcrumb trail for it. Breadcrumbs replace the raw URL
     * line in the search result with a readable path — cheap and visible.
     *
     * @return array<int, array<string, mixed>>
     */
    private function webPageNodes(): array
    {
        if (! $this->page instanceof PageDTO) {
            return [];
        }

        $url = $this->page->url();

        $webPage = array_filter([
            '@type' => 'WebPage',
            '@id' => $url,
            'url' => $url,
            'name' => $this->page->title,
            'description' => $this->page->description,
            'inLanguage' => $this->language(),
            'dateModified' => $this->page->lastModificationDate->toIso8601String(),
            'isPartOf' => ['@id' => $this->id(self::WEBSITE_ID)],
            'about' => ['@id' => $this->id(self::ORGANIZATION_ID)],
            'primaryImageOfPage' => ($image = NewsImage::crawlable($this->page->image, config()->integer('seo.image_width'))) !== null
                ? ['@type' => 'ImageObject', 'url' => $image]
                : null,
        ], fn (mixed $value): bool => $value !== null && $value !== '');

        $breadcrumb = $this->breadcrumbList($url);

        return $breadcrumb === null ? [$webPage] : [$webPage, $breadcrumb];
    }

    /**
     * Builds the trail from the URL path.
     *
     * Every intermediate crumb is resolved against the router first: paths like
     * /rechtliches or /ki/llm-analytics's parent are not always real routes, and
     * a breadcrumb item linking to a 404 is worse than a shorter trail.
     *
     * Returns null on the start page — a single-item breadcrumb is noise.
     *
     * @return array<string, mixed>|null
     */
    private function breadcrumbList(string $url): ?array
    {
        if ($this->page?->routeKey === 'start.index') {
            return null;
        }

        $segments = collect(explode('/', trim((string) parse_url($url, PHP_URL_PATH), '/')))
            ->filter(fn (string $segment): bool => $segment !== '')
            ->values();

        $items = collect([[
            '@type' => 'ListItem',
            'position' => 1,
            'name' => Company::legalName(),
            'item' => $this->localizedStartUrl(),
        ]]);

        $carry = $this->baseUrl();
        $lastIndex = $segments->count() - 1;

        $segments->each(function (string $segment, int $index) use (&$carry, $items, $lastIndex): void {
            $carry .= '/'.$segment;

            $name = $index === $lastIndex
                ? $this->currentPageName()
                : $this->resolvedPageName($carry);

            // An intermediate segment that resolves to no page of its own is
            // not a level a user could navigate to — leave it out entirely.
            if ($name === null) {
                return;
            }

            $items->push([
                '@type' => 'ListItem',
                'position' => $items->count() + 1,
                'name' => $name,
                'item' => $carry,
            ]);
        });

        if ($items->count() < 2) {
            return null;
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $url.'#breadcrumb',
            'itemListElement' => $items->all(),
        ];
    }

    /**
     * Title of the page being rendered, stripped of the " – codebar Solutions AG"
     * suffix that belongs in <title> but not in a breadcrumb.
     */
    private function currentPageName(): ?string
    {
        if (! $this->page instanceof PageDTO || $this->page->title === '') {
            return null;
        }

        return $this->shortTitle($this->page->title);
    }

    /**
     * Looks up the title of an intermediate URL by matching it against the
     * router, then reusing the existing PageAction lookup for its route name.
     * Returns null when the URL is not a routable page.
     */
    private function resolvedPageName(string $url): ?string
    {
        try {
            $route = app('router')->getRoutes()->match(Request::create($url));
        } catch (Throwable) {
            return null;
        }

        $routeName = $route->getName();

        if (! is_string($routeName) || $routeName === '') {
            return null;
        }

        // Route names are locale-prefixed ("de-ch.ai.index"); PageAction keys
        // are not, so drop the prefix before looking the page up.
        $routeKey = Str::after($routeName, '.');

        $page = (new PageAction(routeName: $routeKey))->default();

        return $page instanceof PageDTO && $page->title !== ''
            ? $this->shortTitle($page->title)
            : null;
    }

    private function shortTitle(string $title): string
    {
        return Str::of($title)
            ->before(' – ')
            ->before(' | ')
            ->trim()
            ->toString();
    }

    /**
     * @param  array{street: string, postal_code: string, city: string, country: string}|null  $location
     * @return array<string, mixed>|null
     */
    public function postalAddress(?array $location): ?array
    {
        if ($location === null) {
            return null;
        }

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $location['street'],
            'postalCode' => $location['postal_code'],
            'addressLocality' => $location['city'],
            'addressCountry' => $location['country'],
        ];
    }

    private function localizedStartUrl(): string
    {
        return route(Str::slug(app()->getLocale()).'.start.index', absolute: true);
    }

    private function language(): string
    {
        return str_replace('_', '-', app()->getLocale());
    }

    private function baseUrl(): string
    {
        return Company::baseUrl();
    }

    private function id(string $fragment): string
    {
        return $this->baseUrl().'/'.$fragment;
    }

    private function asset(string $path): string
    {
        return $path === '' ? '' : $this->baseUrl().'/'.ltrim($path, '/');
    }
}
