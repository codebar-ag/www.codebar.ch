<?php

namespace App\Actions;

use App\DTO\ContactDTO;
use App\Enums\ContactSectionEnum;
use App\Models\Configuration;
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
    public function configuration(string $locale): ?Configuration
    {
        $key = Str::slug("configuration_{$locale}");

        return Cache::rememberForever($key, function () {
            return Configuration::first();
        });
    }

    public function products(string $locale): Collection
    {
        $key = Str::slug("products_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Product::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    public function services(string $locale): Collection
    {
        $key = Str::slug("services_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Service::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    public function news(string $locale): Collection
    {
        $key = Str::slug("news_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return News::where('locale', $locale)->whereNotNull('published_at')->orderByDesc('published_at')->get();
        });
    }

    public function technologies(string $locale): Collection
    {
        $key = Str::slug("technologies_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return Technology::where('locale', $locale)->where('published', true)->orderBy('order')->get();
        });
    }

    public function openSource(string $locale): Collection
    {
        $key = Str::slug("open_source_published_{$locale}");

        return Cache::rememberForever($key, function () use ($locale) {
            return OpenSource::where('locale', $locale)->where('published', true)->orderByDesc('downloads')->get();
        });
    }

    public function contacts(string $locale): object
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
