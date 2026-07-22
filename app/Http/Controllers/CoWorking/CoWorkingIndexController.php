<?php

namespace App\Http\Controllers\CoWorking;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CoWorkingIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('app.co-working.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'co-working.index'))->default(),
            'services' => $this->services(),
            'pricing' => [
                'name' => __('Single workstation'),
                'price_chf' => 750,
                'period' => __('month'),
            ],
            'optionalServices' => [
                ['name' => __('Monitor & video camera'), 'price' => __('On request')],
                ['name' => __('Parking with EV charging'), 'price' => __('from CHF 250.00 / month')],
                ['name' => __('Fibre upgrade up to 5 Gbit/s'), 'price' => __('On request')],
                ['name' => __('Static public IP'), 'price' => __('On request')],
            ],
            'rentalConditions' => [
                'minimum_months' => 12,
                'notice_months' => 3,
                'deposit_text' => __('3 months\' rent per workstation'),
            ],
        ]);
    }

    private function services(): array
    {
        return [
            ['category' => __('Workplace'), 'title' => __('Height-adjustable desk'), 'teaser' => __('LO-Next system by Lista Office AG, plus storage for personal materials.')],
            ['category' => __('Workplace'), 'title' => __('Ergonomic chair'), 'teaser' => __('Wilkhahn seating designed for long, healthy workdays.')],
            ['category' => __('Workplace'), 'title' => __('Meeting pod'), 'teaser' => __('Framery One pod for calls and small meetings.')],
            ['category' => __('Workplace'), 'title' => __('Meeting room with 50″ monitor'), 'teaser' => __('Includes video-call infrastructure for team meetings.')],
            ['category' => __('IT & connectivity'), 'title' => __('250 Mbit/s private network'), 'teaser' => __('With 5G failover for uninterrupted connectivity.')],
            ['category' => __('IT & connectivity'), 'title' => __('Managed enterprise infrastructure'), 'teaser' => __('Integrated into a professionally managed enterprise infrastructure.')],
            ['category' => __('IT & connectivity'), 'title' => __('Printer & scanner'), 'teaser' => __('Professional, available to all members.')],
            ['category' => __('Building'), 'title' => __('24/7 access'), 'teaser' => __('Keyless entry via RFID & app — no physical keys.')],
            ['category' => __('Building'), 'title' => __('Business address & mailbox'), 'teaser' => __('Use the address as your registered company location.')],
            ['category' => __('Building'), 'title' => __('Fully-equipped kitchen'), 'teaser' => __('Including coffee and a Quooker water dispenser.')],
            ['category' => __('Building'), 'title' => __('Weekly professional cleaning'), 'teaser' => __('Office, kitchen and sanitary areas — every Wednesday morning.')],
            ['category' => __('Services'), 'title' => __('On-site IT support'), 'teaser' => __('A direct line to the on-site tech team — one hour of consulting per month included.')],
        ];
    }
}
