<?php

declare(strict_types=1);

namespace App\Http\Controllers\Erp;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Models\OdooPriceSnapshot;
use App\Seo\SchemaNodes;
use App\Support\OdooPricing;
use App\Support\OdooPricingSelection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ErpOdooPricingShowController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        OdooPriceSnapshot::apply();

        $query = $request->query();
        $requested = OdooPricingSelection::isRequested($query);
        $selection = $requested ? OdooPricingSelection::fromQuery($query) : OdooPricingSelection::defaults();

        if ($requested && ! $selection->matches($query)) {
            return redirect()->to(localized_route('erp.systems.pricing.show', [...$query, ...$selection->query()]));
        }

        $page = (new PageAction(locale: null, routeName: 'erp.systems.pricing.show'))->default();

        /** @var array<string, array{question: string, answer: string}> $faq */
        $faq = trans('components.odoo_pricing.faq.items');

        return view('app.erp.odoo.pricing')->with([
            'page' => $page,
            'pricing' => OdooPricing::calculatorConfig(),
            'labels' => trans('components.odoo_pricing.labels'),
            'selection' => $selection,
            'result' => $selection->result(),
            'summaryUrl' => localized_route('erp.systems.pricing.summary'),
            'faq' => $faq,
            'checkedAt' => Carbon::parse(config()->string('odoo-pricing.checked_at')),
            'schema' => $page instanceof PageDTO ? SchemaNodes::faqPage($page, array_values($faq)) : [],
        ]);
    }
}
