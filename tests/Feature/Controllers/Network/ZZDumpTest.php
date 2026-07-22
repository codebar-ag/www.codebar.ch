<?php

use App\Actions\PageAction;
use App\Enums\LocaleEnum;
use App\Enums\NetworkCategoryEnum;
use App\Models\Network;

it('renders view directly', function () {
    Network::factory()->create([
        'key' => 'docuware',
        'locale' => LocaleEnum::DE->value,
        'name' => 'DocuWare',
        'category' => NetworkCategoryEnum::SOFTWARE->value,
        'tier_label' => 'Silver Partner',
        'excerpt' => 'DMS/ECM',
    ]);
    $networks = Network::query()->published()->active()->get();
    $groups = ['software' => $networks];
    $finder = app('view')->getFinder();
    dump('resolved path: '.$finder->find('app.network.index'));
    $html = view('app.network.index', [
        'page' => (new PageAction(locale: null, routeName: 'network.index'))->default(),
        'groups' => collect($groups),
    ])->render();
    dump('tier in direct render: '.var_export(str_contains($html, 'Silver Partner'), true));
    expect(true)->toBeTrue();
});
