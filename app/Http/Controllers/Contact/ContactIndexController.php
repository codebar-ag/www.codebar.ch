<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contact;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use Illuminate\View\View;

class ContactIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.contact.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'contact.index'))->default(),
            'openingHours' => config('company.opening_hours'),
            'locations' => config('company.locations'),
            'schema' => SchemaNodes::locations(),
        ]);
    }
}
