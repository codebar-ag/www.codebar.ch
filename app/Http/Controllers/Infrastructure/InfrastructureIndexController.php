<?php

declare(strict_types=1);

namespace App\Http\Controllers\Infrastructure;

use App\Actions\PageAction;
use App\Http\Controllers\Controller;
use App\Models\InfrastructureStack;
use App\Support\Infrastructure\Catalog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InfrastructureIndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        $slug = $request->query('site');
        $hasSlug = is_string($slug) && $slug !== '';
        $customer = $hasSlug ? InfrastructureStack::findBySlug($slug) : null;
        $isCustomer = $customer !== null;
        $stack = $customer ?? InfrastructureStack::default();

        abort_if($stack === null, 404);

        $layers = Catalog::layers($stack);
        $query = $isCustomer ? ['site' => $slug] : [];
        $urls = [];

        foreach ($layers as $layer) {
            foreach ($layer['stations'] as $station) {
                $urls[$station['id']] = localized_route('infrastructure.show', ['station' => $station['id']] + $query);
            }
        }

        $page = (new PageAction(locale: null, routeName: 'infrastructure.index'))->default();

        if ($page !== null && $hasSlug) {
            $page->robots = 'noindex,nofollow';
        }

        return view('app.infrastructure.index')->with([
            'page' => $page,
            'layers' => $layers,
            'urls' => $urls,
            'data' => [
                'layers' => $layers,
                'edges' => Catalog::edges($layers),
                'urls' => $urls,
                'auto' => $isCustomer,
                'site' => [
                    'domain' => $stack->domain,
                    'image' => $stack->cover(),
                    'alt' => __('components.infrastructure.site_alt', ['domain' => $stack->domain]),
                ],
                'labels' => [
                    'back' => __('components.infrastructure.back'),
                    'progress' => __('components.infrastructure.progress'),
                    'close' => __('components.infrastructure.close'),
                    'processing' => __('components.infrastructure.processing'),
                    'provider' => __('components.infrastructure.provider'),
                    'origin' => __('components.infrastructure.origin'),
                    'operation' => __('components.infrastructure.operation'),
                    'details' => __('components.infrastructure.details'),
                ],
            ],
        ]);
    }
}
