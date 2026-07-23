<?php

namespace App\Http\Controllers\Network;

use App\Actions\PageAction;
use App\Enums\NetworkCategoryEnum;
use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NetworkIndexController extends Controller
{
    public function __invoke(): View
    {
        $networks = Network::query()
            ->published()
            ->active()
            ->with('publishedUsers')
            ->orderBy('sort')
            ->get();

        $groups = collect(NetworkCategoryEnum::cases())
            ->mapWithKeys(fn (NetworkCategoryEnum $category): array => [
                $category->value => $networks->where('category', $category),
            ])
            ->filter(fn (Collection $items): bool => $items->isNotEmpty());

        return view('app.network.index')->with([
            'page' => (new PageAction(locale: null, routeName: 'network.index'))->default(),
            'groups' => $groups,
        ]);
    }
}
