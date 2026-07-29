<?php

declare(strict_types=1);

namespace App\Seo;

use App\DTO\ContactDTO;
use App\DTO\PageDTO;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Service;
use App\Support\NewsImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Page-specific schema.org nodes, appended to the global graph built by
 * SchemaGraph via the `schema` prop on <x-app-layout>.
 *
 * Built in controllers rather than in Blade so the shapes stay testable and
 * the views keep to markup.
 *
 * @phpstan-type SchemaNode array<string, mixed>
 */
class SchemaNodes
{
    /**
     * One LocalBusiness node per physical location.
     *
     * ProfessionalService is the LocalBusiness subtype that fits a software
     * consultancy. Each branch gets its own @id and points back at the
     * Organization, so Google can attach the right opening hours and address
     * to the right Business Profile.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function locations(): array
    {
        $organizationId = self::organizationId();

        return collect(Company::locations())->map(fn (array $location): array => array_filter([
            '@type' => 'ProfessionalService',
            '@id' => $organizationId.'-'.$location['key'],
            'name' => Company::legalName().' — '.$location['city'],
            'parentOrganization' => ['@id' => $organizationId],
            'url' => Company::baseUrl(),
            'email' => Company::email(),
            'telephone' => Company::phone(),
            'vatID' => Company::uid(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $location['street'],
                'postalCode' => $location['postal_code'],
                'addressLocality' => $location['city'],
                'addressCountry' => $location['country'],
            ],
            'hasMap' => $location['map_url'],
            'openingHoursSpecification' => self::openingHours(),
            'areaServed' => 'CH',
            // openingHoursSpecification is empty when every day is closed.
        ], fn (mixed $value): bool => $value !== []))
            ->values()
            ->all();
    }

    /**
     * Closed days are omitted rather than emitted with null times — schema.org
     * treats an absent day as closed.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function openingHours(): array
    {
        return collect(Company::openingHours())
            ->filter(fn (array $entry): bool => $entry['open'] !== null && $entry['close'] !== null)
            ->map(fn (array $entry): array => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => 'https://schema.org/'.$entry['day'],
                'opens' => $entry['open'],
                'closes' => $entry['close'],
            ])
            ->values()
            ->all();
    }

    /**
     * ProfilePage wrapper plus a Person node per team member.
     *
     * @param  Collection<int, ContactDTO>  $contacts
     * @return array<int, array<string, mixed>>
     */
    public static function team(Collection $contacts, PageDTO $page): array
    {
        $organizationId = self::organizationId();

        $people = $contacts
            ->unique(fn (ContactDTO $contact): string => $contact->name)
            ->map(fn (ContactDTO $contact): array => array_filter([
                '@type' => 'Person',
                'name' => $contact->name,
                'jobTitle' => $contact->role,
                'image' => $contact->image !== '' ? $contact->image : null,
                'worksFor' => ['@id' => $organizationId],
                'sameAs' => self::linkedIn($contact),
            ], fn (mixed $value): bool => $value !== null && $value !== []))
            ->values();

        if ($people->isEmpty()) {
            return [];
        }

        return [[
            '@type' => 'ProfilePage',
            '@id' => $page->url().'#profilepage',
            'url' => $page->url(),
            'name' => $page->title,
            'about' => ['@id' => $organizationId],
            'mainEntity' => $people->all(),
        ]];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function blogPosting(News $news, PageDTO $page, string $locale): array
    {
        $organizationId = self::organizationId();

        $tags = $news->tags;

        return [array_filter([
            '@type' => 'BlogPosting',
            '@id' => $page->url().'#article',
            'headline' => $page->title,
            'description' => $page->description,
            'url' => $page->url(),
            'mainEntityOfPage' => ['@id' => $page->url()],
            'inLanguage' => str_replace('_', '-', $locale),
            'datePublished' => $news->published_at?->toIso8601String(),
            'dateModified' => $news->updated_at?->toIso8601String(),
            'author' => self::articleAuthor($news, $organizationId),
            'publisher' => ['@id' => $organizationId],
            'image' => NewsImage::src($news->hero_image, config()->integer('seo.image_width')),
            'keywords' => is_array($tags) ? array_values($tags) : null,
        ], fn (mixed $value): bool => $value !== null && $value !== [])];
    }

    /**
     * A linked contact becomes a real Person node with picture and LinkedIn profile,
     * which is what Google reads for author authority. Without one the organisation
     * stays the author, as before.
     *
     * @return array<string, mixed>
     */
    private static function articleAuthor(News $news, string $organizationId): array
    {
        $contact = $news->authorContact;

        if ($contact !== null) {
            return array_filter([
                '@type' => 'Person',
                'name' => $contact->name,
                'image' => $contact->image !== '' ? $contact->image : null,
                'worksFor' => ['@id' => $organizationId],
                'sameAs' => is_array($contact->icons) ? Arr::get($contact->icons, 'linkedin') : null,
            ], fn (mixed $value): bool => $value !== null);
        }

        return filled($news->author)
            ? ['@type' => 'Person', 'name' => $news->author]
            : ['@id' => $organizationId];
    }

    /**
     * The news index as a Blog with its posts listed.
     *
     * @param  Collection<int, News>  $news
     * @return array<int, array<string, mixed>>
     */
    public static function blog(Collection $news, PageDTO $page, string $locale): array
    {
        $organizationId = self::organizationId();

        return [array_filter([
            '@type' => 'Blog',
            '@id' => $page->url().'#blog',
            'url' => $page->url(),
            'name' => $page->title,
            'description' => $page->description,
            'inLanguage' => str_replace('_', '-', $locale),
            'publisher' => ['@id' => $organizationId],
            'blogPost' => $news->map(fn (News $item): array => array_filter([
                '@type' => 'BlogPosting',
                'headline' => $item->getTranslation('title', $locale),
                'url' => route(
                    Str::slug($locale).'.news.show',
                    ['locale' => $locale, 'news' => $item],
                    true
                ),
                'datePublished' => $item->published_at?->toIso8601String(),
            ], fn (mixed $value): bool => $value !== null && $value !== ''))->all(),
        ], fn (mixed $value): bool => $value !== null && $value !== [])];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function softwareSourceCode(OpenSource $openSource, PageDTO $page, string $locale): array
    {
        $organizationId = self::organizationId();

        $tags = $openSource->tags;

        return [array_filter([
            '@type' => 'SoftwareSourceCode',
            '@id' => $page->url().'#software',
            'name' => $page->title,
            'description' => $page->description,
            'url' => $page->url(),
            'codeRepository' => filled($openSource->link) ? $openSource->link : null,
            'programmingLanguage' => filled($openSource->primary_language)
                ? $openSource->primary_language
                : null,
            'author' => ['@id' => $organizationId],
            'inLanguage' => str_replace('_', '-', $locale),
            'keywords' => is_array($tags) ? array_values($tags) : null,
        ], fn (mixed $value): bool => $value !== null && $value !== [])];
    }

    /**
     * Service nodes for the expertise page.
     *
     * These produce no rich result on their own — Service is not in Google's
     * rich-result gallery. They are here because they tell search engines and
     * LLMs what this company actually sells, which is what gets us surfaced in
     * generated answers.
     *
     * @param  Collection<int, Service>  $services
     * @return array<int, array<string, mixed>>
     */
    public static function services(Collection $services, string $locale): array
    {
        $organizationId = self::organizationId();

        return $services->map(fn (Service $service): array => array_filter([
            '@type' => 'Service',
            'name' => $service->getTranslation('name', $locale),
            'description' => $service->getTranslation('teaser', $locale),
            'provider' => ['@id' => $organizationId],
            'areaServed' => 'CH',
        ], fn (mixed $value): bool => $value !== null && $value !== ''))
            ->values()
            ->all();
    }

    private static function organizationId(): string
    {
        return Company::baseUrl().'/'.SchemaGraph::ORGANIZATION_ID;
    }

    /**
     * @return array<int, string>
     */
    private static function linkedIn(ContactDTO $contact): array
    {
        $url = Arr::get($contact->icons, 'linkedin');

        return is_string($url) && $url !== '' ? [$url] : [];
    }
}
