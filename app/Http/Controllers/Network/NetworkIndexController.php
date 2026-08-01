<?php

declare(strict_types=1);

namespace App\Http\Controllers\Network;

use App\Actions\PageAction;
use App\Actions\ViewDataAction;
use App\Enums\NetworkCategoryEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NetworkIndexController extends Controller
{
    public function __invoke(ViewDataAction $viewData): View
    {
        $networks = $viewData->networks();

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
