<?php

namespace App\Http\Controllers\AboutUs;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutUsIndexController extends Controller
{
    public function __invoke(ViewDataAction $data): View
    {
        $locale = app()->getLocale();

        return view('app.about-us.index')->with([
            'page' => PageAction::for('about-us.index', $locale),
            'contacts' => $data->contacts($locale),
            'milestones' => $data->milestones($locale),
            'pillars' => $data->pillars($locale),
        ]);
    }
}
