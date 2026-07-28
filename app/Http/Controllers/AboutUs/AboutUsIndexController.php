<?php

namespace App\Http\Controllers\AboutUs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\ContactDTO;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use stdClass;

class AboutUsIndexController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        $page = (new PageAction(locale: null, routeName: 'about-us.index'))->default();
        $contacts = (new ViewDataAction)->contacts($locale);

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
     * @return Collection<int, ContactDTO>
     */
    private function flatten(stdClass $contacts): Collection
    {
        /** @var Collection<int, ContactDTO> $flattened */
        $flattened = collect((array) $contacts)
            ->flatMap(fn (mixed $group): array => $group instanceof Collection ? $group->all() : [])
            ->filter(fn (mixed $contact): bool => $contact instanceof ContactDTO)
            ->values();

        return $flattened;
    }
}
