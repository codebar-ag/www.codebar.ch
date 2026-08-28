<?php

declare(strict_types=1);

use App\Security\Replacers\CspHeaderReplacer;
use Symfony\Component\HttpFoundation\Response;

function responseWithCspHeaders(): Response
{
    $response = new Response('ok');
    $response->headers->set('Content-Security-Policy', "script-src 'nonce-frozen'");
    $response->headers->set('Content-Security-Policy-Report-Only', "script-src 'nonce-frozen'");
    $response->headers->set('X-Frame-Options', 'DENY');

    return $response;
}

it('strips the csp headers before a response is cached', function () {
    $response = responseWithCspHeaders();

    (new CspHeaderReplacer)->prepareResponseToCache($response);

    expect($response->headers->has('Content-Security-Policy'))->toBeFalse()
        ->and($response->headers->has('Content-Security-Policy-Report-Only'))->toBeFalse()
        ->and($response->headers->get('X-Frame-Options'))->toBe('DENY');
})->group('unit', 'security');

it('strips a stale csp header from a cached response before it is served', function () {
    $response = responseWithCspHeaders();

    (new CspHeaderReplacer)->replaceInCachedResponse($response);

    expect($response->headers->has('Content-Security-Policy'))->toBeFalse()
        ->and($response->headers->has('Content-Security-Policy-Report-Only'))->toBeFalse()
        ->and($response->headers->get('X-Frame-Options'))->toBe('DENY');
})->group('unit', 'security');
