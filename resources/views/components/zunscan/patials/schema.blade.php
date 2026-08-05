@props(['title', 'description', 'canonical', 'image' => null])

@php
    // A deliberately small graph compared to the main site's SchemaGraph, which
    // is built around codebar's own config/company.php and would describe the
    // wrong organisation on this domain. Same shape and same discipline though:
    // every node has an @id, and nodes reference each other by @id rather than
    // repeating themselves.
    $root = request()->getSchemeAndHttpHost();
    $company = config('zunscan.company');
    $locale = str_replace('_', '-', app()->getLocale());
    $home = zunscan_route('start.index');

    $address = [
        '@type' => 'PostalAddress',
        'streetAddress' => $company['street'],
        'postalCode' => $company['postal_code'],
        'addressLocality' => $company['locality'],
        'addressRegion' => 'BL',
        'addressCountry' => 'CH',
    ];

    $organization = [
        '@type' => 'Organization',
        '@id' => $root.'/#organization',
        'name' => 'zunscan.ch',
        'legalName' => $company['legal_name'],
        'url' => $root.'/',
        'email' => $company['email'],
        'vatID' => $company['uid'],
        'address' => $address,
        'areaServed' => ['@type' => 'Place', 'name' => 'Nordwestschweiz'],
        // The joint venture, stated in the markup as well as in the copy.
        'parentOrganization' => array_map(fn (array $person): array => [
            '@type' => 'Organization',
            'name' => $person['company'],
            'url' => $person['website'],
        ], config('zunscan.people')),
        'contactPoint' => array_map(fn (array $person): array => [
            '@type' => 'ContactPoint',
            'contactType' => 'sales',
            'name' => $person['name'],
            'email' => $person['email'],
            'telephone' => str_replace(' ', '', $person['phone']),
            'availableLanguage' => ['de-CH', 'en-CH'],
        ], config('zunscan.people')),
    ];

    if ($image) {
        $organization['image'] = $image;
    }

    $graph = [
        $organization,
        [
            // The scanning centre as a findable local business, with the same
            // address as the operating company.
            '@type' => 'LocalBusiness',
            '@id' => $root.'/#localbusiness',
            'name' => 'zunscan.ch',
            'url' => $root.'/',
            'email' => $company['email'],
            'address' => $address,
            'parentOrganization' => ['@id' => $root.'/#organization'],
            'priceRange' => 'CHF',
        ],
        [
            '@type' => 'WebSite',
            '@id' => $root.'/#website',
            'url' => $root.'/',
            'name' => 'zunscan.ch',
            'inLanguage' => $locale,
            'publisher' => ['@id' => $root.'/#organization'],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => $locale,
            'isPartOf' => ['@id' => $root.'/#website'],
            'about' => ['@id' => $root.'/#organization'],
        ],
    ];

    // Breadcrumb only where there is a trail to describe — on the start page it
    // would be a single self-referential item, which Google ignores anyway.
    if ($canonical !== $home) {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical.'#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'zunscan.ch', 'item' => $home],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $title],
            ],
        ];
    }

    $payload = json_encode([
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp

{{--
    Same handling as the main site's layouts/_partials/_schema.blade.php: a
    <script> with a non-JavaScript type is a data block, never executed, so the
    strict script-src does not apply and no nonce is needed. Escaping is for the
    </script> sequence only — this payload comes from our own config, never from
    request input.
--}}
<script type="application/ld+json">{!! str_replace('</', '<\/', $payload) !!}</script>
