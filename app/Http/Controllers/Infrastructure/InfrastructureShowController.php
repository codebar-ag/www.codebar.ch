<?php

declare(strict_types=1);

namespace App\Http\Controllers\Infrastructure;

use App\DTO\PageDTO;
use App\Http\Controllers\Controller;
use App\Models\InfrastructureStack;
use App\Support\Infrastructure\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InfrastructureShowController extends Controller
{
    public function __invoke(Request $request, string $station): View
    {
        $slug = $request->query('kunde');
        $isCustomer = is_string($slug) && $slug !== '';
        $stack = $isCustomer ? InfrastructureStack::findBySlug($slug) : InfrastructureStack::default();

        abort_if($stack === null, 404);

        $query = $isCustomer ? ['kunde' => $slug] : [];

        foreach (Catalog::layers($stack) as $layer) {
            foreach ($layer['stations'] as $candidate) {
                if ($candidate['id'] === $station) {
                    return view('app.infrastructure.show')->with([
                        'page' => new PageDTO(
                            locale: app()->getLocale(),
                            routeKey: 'infrastructure.show',
                            routeName: Str::slug(app()->getLocale()).'.infrastructure.show',
                            title: $candidate['name'].' · '.$candidate['provider'],
                            lastModificationDate: now(),
                            robots: $isCustomer ? 'noindex,nofollow' : 'index,follow',
                            routeParameters: ['station' => $station],
                            description: $candidate['text'],
                        ),
                        'layer' => $layer,
                        'station' => $candidate,
                        'index' => localized_route('infrastructure.index', $query),
                    ]);
                }
            }
        }

        abort(404);
    }
}
