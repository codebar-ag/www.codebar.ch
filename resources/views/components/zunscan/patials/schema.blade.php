@props(['title', 'description', 'canonical'])

@php
    // A deliberately small graph. The main site's SchemaGraph is not reused: it
    // is built around codebar's own config/company.php and would describe the
    // wrong organisation on this domain.
    $root = request()->getSchemeAndHttpHost();

    $company = config('zunscan.company');

    $graph = [
        [
            '@type' => 'Organization',
            '@id' => $root.'/#organization',
            'name' => 'zunscan.ch',
            'legalName' => $company['legal_name'],
            'url' => $root.'/',
            'email' => $company['email'],
            'vatID' => $company['uid'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $company['street'],
                'postalCode' => $company['postal_code'],
                'addressLocality' => $company['locality'],
                'addressCountry' => 'CH',
            ],
            // The joint venture, stated in the markup as well as in the copy.
            'parentOrganization' => array_map(fn (array $person): array => [
                '@type' => 'Organization',
                'name' => $person['company'],
                'url' => $person['website'],
            ], config('zunscan.people')),
        ],
        [
            '@type' => 'WebSite',
            '@id' => $root.'/#website',
            'url' => $root.'/',
            'name' => 'zunscan.ch',
            'inLanguage' => str_replace('_', '-', app()->getLocale()),
            'publisher' => ['@id' => $root.'/#organization'],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical.'#webpage',
            'url' => $canonical,
            'name' => $title,
            'description' => $description,
            'inLanguage' => str_replace('_', '-', app()->getLocale()),
            'isPartOf' => ['@id' => $root.'/#website'],
        ],
    ];

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
