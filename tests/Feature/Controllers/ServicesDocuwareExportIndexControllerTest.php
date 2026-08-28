<?php

declare(strict_types=1);

// use App\Enums\LocaleEnum;
// use App\Enums\SessionKeyEnum;
// use App\Models\Service;
// use Database\Seeders\PagesTableSeeder;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Str;

// use function Pest\Laravel\get;
// use function Pest\Laravel\seed;
// use function Pest\Laravel\withSession;

/*
it('returns 200 for the DMS/ECM page in both locales', function (string $locale) {
    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.services.dms-ecm.index'))
        ->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('services');

it('returns 200 for the export page in both locales', function (string $locale) {
    withSession([SessionKeyEnum::LANGUAGE->value => $locale])
        ->get(route(Str::slug($locale).'.services.dms-ecm.docuware-export.index'))
        ->assertOk();
})->with([LocaleEnum::DE->value, LocaleEnum::EN->value])->group('services');

it('renders the DMS/ECM service body on its own page', function () {
    Service::factory()->create([
        'slug' => 'dms-ecm-consulting',
        'content' => ['de_CH' => '## Ablage und Indexierung', 'en_CH' => '## Filing and indexing'],
        'published' => true,
    ]);

    get(route('de-ch.services.dms-ecm.index'))
        ->assertOk()
        ->assertSee('Ablage und Indexierung');
})->group('services');

it('names both export modes on the export page', function () {
    get(route('de-ch.services.dms-ecm.docuware-export.index'))
        ->assertOk()
        ->assertSee(__('components.docuware.export.modes.once.title'))
        ->assertSee(__('components.docuware.export.modes.scheduled.title'));
})->group('services');

it('links from the export page back to its parent', function () {
    get(route('de-ch.services.dms-ecm.docuware-export.index'))
        ->assertOk()
        ->assertSee(route('de-ch.services.dms-ecm.index'));
})->group('services');

it('describes the service as structured data', function () {
    seed(PagesTableSeeder::class);

    get(route('de-ch.services.dms-ecm.docuware-export.index'))
        ->assertOk()
        ->assertSee('DocuWare document export');
})->group('services');

it('lists both new pages in the sitemap', function () {
    seed(PagesTableSeeder::class);

    Cache::flush();

    get('/sitemap.xml')
        ->assertOk()
        ->assertSee(route('de-ch.services.dms-ecm.index'), false)
        ->assertSee(route('de-ch.services.dms-ecm.docuware-export.index'), false)
        ->assertSee(route('en-ch.services.dms-ecm.docuware-export.index'), false);
})->group('services');
*/
