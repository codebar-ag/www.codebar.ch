<?php

namespace App\Http\Controllers\CoWorking;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CoWorkingIndexController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        return redirect()->route(Str::slug(app()->getLocale()).'.start.index');

        /*        return view('app.co-working.index')->with([
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
                ]);*/
    }
}
