<?php

namespace App\Http\Controllers\Contact;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactIndexController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __invoke(): View
    {
        $openingHours = [
            ['day' => 'Monday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Tuesday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Wednesday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Thursday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Friday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Saturday', 'open' => '08:00', 'close' => '18:00'],
            ['day' => 'Sunday', 'open' => null, 'close' => null],
        ];

        return view('app.contact.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'contact.index'))->default(),
            'openingHours' => $openingHours,
        ]);
    }
}
