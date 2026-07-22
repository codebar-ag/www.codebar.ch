<?php

use App\DTO\PageDTO;
use Illuminate\Support\Carbon;

it('builds the absolute url from the route name and parameters', function () {
    $page = new PageDTO(
        locale: 'de_CH',
        routeKey: 'start.index',
        routeName: 'de-ch.start.index',
        title: 'Start',
        lastModificationDate: Carbon::now(),
    );

    expect($page->url())->toBe(route('de-ch.start.index', [], true));
})->group('unit', 'dto');
