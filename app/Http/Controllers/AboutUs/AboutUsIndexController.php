<?php

declare(strict_types=1);

namespace App\Http\Controllers\AboutUs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\ContactDTO;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AboutUsIndexController extends Controller
{
    public function __invoke(ViewDataAction $viewData): View
    {
        $locale = app()->getLocale();

        $page = (new PageAction(locale: null, routeName: 'about-us.index'))->default();
        $contacts = $viewData->contacts($locale);

        return view('app.about-us.index')->with([
            'page' => $page,
            'contacts' => $contacts,
            'schema' => $page instanceof PageDTO
                ? SchemaNodes::team($this->flatten($contacts), $page)
                : [],
        ]);
    }

    /**
     * Flattens the section-keyed contact groups into one list — the schema
     * cares about the people, not about which block they render in.
     *
     * @param  Collection<string, Collection<int, ContactDTO>>  $contacts
     * @return Collection<int, ContactDTO>
     */
    private function flatten(Collection $contacts): Collection
    {
        /** @var Collection<int, ContactDTO> $flattened */
        $flattened = $contacts->flatMap(fn (Collection $group): array => $group->all())->values();

        return $flattened;
    }
}
