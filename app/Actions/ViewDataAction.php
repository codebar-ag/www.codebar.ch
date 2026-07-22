<?php

namespace App\Actions;

use App\DTO\ContactDTO;
use App\Enums\AiModelCategoryEnum;
use App\Enums\ContactSectionEnum;
use App\Models\AiModel;
use App\Models\Contact;
use App\Models\News;
use App\Models\OpenSource;
use App\Models\Product;
use App\Models\Service;
use App\Models\Technology;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ViewDataAction
{
    /**
     * @return Collection<int, Product>
     */
    public function products(string $locale): Collection
    {
        $key = Str::slug("products_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Product::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, Service>
     */
    public function services(string $locale): Collection
    {
        $key = Str::slug("services_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Service::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, News>
     */
    public function news(string $locale): Collection
    {
        $key = Str::slug("news_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return News::where('locale', $locale)->whereNotNull('published_at')->orderByDesc('published_at')->get();
        });
    }

    /**
     * @return Collection<int, Technology>
     */
    public function technologies(string $locale): Collection
    {
        $key = Str::slug("technologies_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Technology::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    /**
     * @return Collection<int, array{category: AiModelCategoryEnum, models: Collection<int, AiModel>}>
     */
    public function aiModelGroups(): Collection
    {
        return Cache::rememberForever('ai_models_active', function () {
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
        return Cache::rememberForever('ai_models_archived', function () {
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
        $key = Str::slug("open_source_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return OpenSource::where('locale', $locale)->where('published', true)->orderByDesc('downloads')->get();
        });
    }

    public function contacts(string $locale): \stdClass
    {
        $key = Str::slug("contacts_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            $publishedContacts = Contact::query()
                ->where('published', true)
                ->orderBy('name')
                ->get();

            return (object) collect([
                ContactSectionEnum::EMPLOYEES,
                ContactSectionEnum::COLLABORATIONS,
                ContactSectionEnum::BOARD_MEMBERS,
            ])->mapWithKeys(function (string $section) use ($publishedContacts, $locale): array {
                $contacts = $publishedContacts
                    ->filter(function (Contact $contact) use ($section): bool {
                        $sections = $contact->sections ?? [];

                        return array_key_exists($section, $sections);
                    })
                    ->map(fn (Contact $contact) => ContactDTO::fromModel($contact, $section, $locale));

                return [$section => $contacts->values()];
            })->all();
        });
    }
}
