<?php

declare(strict_types=1);

namespace App\Http\Controllers\Erp;

use App\Actions\PageAction;
use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Models\OdooPriceSnapshot;
use App\Support\OdooPricing;
use App\Support\OdooPricingSelection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ErpOdooPricingSummaryController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        OdooPriceSnapshot::apply();

        $query = $request->query();
        $selection = OdooPricingSelection::fromQuery($query);

        if (! $selection->matches($query)) {
            return redirect()->to(localized_route('erp.systems.pricing.summary', [...$query, ...$selection->query()]));
        }

        $result = $selection->result();
        $headline = OdooPricing::headline($result['full_users'], $result['light_users']);

        $page = (new PageAction(locale: null, routeName: 'erp.systems.pricing.summary', routeParameters: $selection->query()))->default();

        if ($page instanceof PageDTO) {
            $page->description = __('components.odoo_pricing.report.description', [
                'users' => $headline,
                'total' => OdooPricing::format($result['total_month'] * 12),
            ]);
        }

        return view('app.erp.odoo.summary')->with([
            'page' => $page,
            'pricing' => OdooPricing::calculatorConfig(),
            'selection' => $selection,
            'result' => $result,
            'headline' => $headline,
            'calculatorUrl' => localized_route('erp.systems.pricing.show', $selection->query()),
            'checkedAt' => Carbon::parse(config()->string('odoo-pricing.checked_at')),
        ]);
    }
}
