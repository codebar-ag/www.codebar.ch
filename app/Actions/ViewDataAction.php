<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTO\ContactDTO;
use App\Enums\AiModelCategoryEnum;
use App\Enums\CacheKeyEnum;
use App\Enums\ContactSectionEnum;
use App\Models\AiModel;
use App\Models\Contact;
use App\Models\JobPosition;
use App\Models\Network;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ViewDataAction
{
    /**
     * @return Collection<int, Product>
     */
    public function products(string $locale): Collection
    {
        $key = CacheKeyEnum::PRODUCTS_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            return Product::where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, Service>
     */
    public function services(string $locale): Collection
    {
        $key = CacheKeyEnum::SERVICES_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            return Service::where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, News>
     */
    public function news(string $locale): Collection
    {
        $key = CacheKeyEnum::NEWS_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            // Eager loaded: the cards read the series title and the author's picture,
            // the index filters on tags, and Model::shouldBeStrict() turns a lazy load
            // into an exception locally.
            return News::with(['series', 'authorContact', 'newsTags'])
                ->published()
                ->orderByDesc('published_at')
                ->get();
        });
    }

    /**
     * @return Collection<int, Technology>
     */
    public function technologies(string $locale): Collection
    {
        $key = CacheKeyEnum::TECHNOLOGIES_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            return Technology::where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, array{category: AiModelCategoryEnum, models: Collection<int, AiModel>}>
     */
    public function aiModelGroups(): Collection
    {
        return Cache::rememberForever(CacheKeyEnum::AI_MODELS_ACTIVE->value, function () {
            return $this->groupAiModelsByCategory(
                AiModel::whereNull('archived_at')->orderBy('order')->get()
            );
        });
    }

    /**
     * @return Collection<int, array{category: AiModelCategoryEnum, models: Collection<int, AiModel>}>
     */
    public function aiModelArchive(): Collection
    {
        return Cache::rememberForever(CacheKeyEnum::AI_MODELS_ARCHIVED->value, function () {
            return $this->groupAiModelsByCategory(
                AiModel::whereNotNull('archived_at')->with('replacedBy')->orderBy('order')->get()
            );
        });
    }

    /**
     * @param  Collection<int, AiModel>  $models
     * @return Collection<int, array{category: AiModelCategoryEnum, models: Collection<int, AiModel>}>
     */
    private function groupAiModelsByCategory(Collection $models): Collection
    {
        return collect(AiModelCategoryEnum::cases())
            ->map(fn (AiModelCategoryEnum $category) => [
                'category' => $category,
                'models' => $models->where('category', $category)->values(),
            ])
            ->filter(fn (array $group) => $group['models']->isNotEmpty())
            ->values();
    }

    /**
     * @return Collection<int, OpenSource>
     */
    public function openSource(string $locale): Collection
    {
        $key = CacheKeyEnum::OPEN_SOURCE_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            return OpenSource::where('published', true)->orderByDesc('downloads')->get();
        });
    }

    /**
     * The published team, grouped by the section each person appears in. Every section
     * is present, empty ones included, so a caller never has to guard the lookup.
     *
     * @return Collection<string, Collection<int, ContactDTO>>
     */
    public function contacts(string $locale): Collection
    {
        $key = CacheKeyEnum::CONTACTS_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () use ($locale) {
            // Ordered by the `sort` field from the YAML files rather than alphabetically:
            // the team page has an intended order that a surname does not express.
            $publishedContacts = Contact::query()
                ->where('published', true)
                ->orderBy('sort')
                ->orderBy('name')
                ->get();

            return collect(ContactSectionEnum::cases())
                ->mapWithKeys(fn (ContactSectionEnum $section): array => [
                    $section->value => $publishedContacts
                        ->filter(fn (Contact $contact): bool => array_key_exists($section->value, $contact->sections ?? []))
                        ->map(fn (Contact $contact): ContactDTO => ContactDTO::fromModel($contact, $section, $locale))
                        ->values(),
                ]);
        });
    }

    /**
     * @return Collection<int, ContactDTO>
     */
    public function contactsInSection(string $locale, ContactSectionEnum $section): Collection
    {
        /** @var Collection<int, ContactDTO> $contacts */
        $contacts = $this->contacts($locale)->get($section->value, new Collection);

        return $contacts;
    }

    /**
     * @return Collection<int, JobPosition>
     */
    public function jobPositions(string $locale): Collection
    {
        $key = CacheKeyEnum::JOB_POSITIONS_PUBLISHED->forLocale($locale);

        return Cache::rememberForever($key, function () {
            return JobPosition::where('published', true)->orderBy('sort')->get();
        });
    }

    /**
     * @return Collection<int, Network>
     */
    public function networks(): Collection
    {
        return Cache::rememberForever(CacheKeyEnum::NETWORKS_PUBLISHED->value, function () {
            return Network::query()
                ->published()
                ->active()
                ->with('publishedUsers')
                ->orderBy('sort')
                ->get();
        });
    }
}
