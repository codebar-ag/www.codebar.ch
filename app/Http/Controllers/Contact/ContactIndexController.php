<?php

namespace App\Http\Controllers\Contact;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.contact.index')->with([
            'page' => PageAction::for('contact.index'),
            'configuration' => site_configuration(),
        ]);
    }
}
