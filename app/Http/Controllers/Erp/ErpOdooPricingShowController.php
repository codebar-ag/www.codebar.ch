<?php

declare(strict_types=1);

namespace App\Http\Controllers\Erp;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Seo\SchemaNodes;
use App\Support\OdooPricing;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ErpOdooPricingShowController extends Controller
{
    public function __invoke(): View
    {
        $page = (new PageAction(locale: null, routeName: 'erp.systems.pricing.show'))->default();

        /** @var array<string, array{question: string, answer: string}> $faq */
        $faq = trans('components.odoo_pricing.faq.items');

        return view('app.erp.odoo.pricing')->with([
            'page' => $page,
            'pricing' => OdooPricing::calculatorConfig(),
            'labels' => trans('components.odoo_pricing.labels'),
            'result' => OdooPricing::calculate(
                config()->integer('odoo-pricing.full_users.default'),
                config()->integer('odoo-pricing.light_users.default'),
            ),
            'faq' => $faq,
            'checkedAt' => Carbon::parse(config()->string('odoo-pricing.checked_at')),
            'schema' => $page instanceof PageDTO ? SchemaNodes::faqPage($page, array_values($faq)) : [],
        ]);
    }
}
