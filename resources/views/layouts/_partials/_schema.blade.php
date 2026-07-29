{{--
    schema.org JSON-LD graph.

    A <script> with a non-JavaScript type is a data block, never executed, so
    our strict CSP (script-src 'self') does not apply to it. If a CSP violation
    ever shows up here, config/csp.php already has nonce support to switch on.

    json_encode output is escaped for the </script> sequence only — the payload
    is built from our own config and DB content, never from request input.
--}}
@php
    $schemaJson = (new \App\Seo\SchemaGraph(
        page: $page ?? null,
        additionalNodes: $schema ?? [],
    ))->toJson();
@endphp

<script type="application/ld+json">{!! str_replace('</', '<\/', $schemaJson) !!}</script>
