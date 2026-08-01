<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\DTO\ContactDTO;
use App\Enums\ContactSectionEnum;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class ContactIndexController extends Controller
{
    public function __invoke(ViewDataAction $viewData): View
    {
        return view('app.contact.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'contact.index'))->default(),
            'openingHours' => config('company.opening_hours'),
            'locations' => config('company.locations'),
            'contactPerson' => $this->contactPerson($viewData),
            'schema' => SchemaNodes::locations(),
        ]);
    }

    private function contactPerson(ViewDataAction $viewData): ?ContactDTO
    {
        $key = config('company.contact_person');

        if (! is_string($key) || $key === '') {
            return null;
        }

        return $viewData
            ->contactsInSection(app()->getLocale(), ContactSectionEnum::EMPLOYEES)
            ->firstWhere('key', $key);
    }
}
