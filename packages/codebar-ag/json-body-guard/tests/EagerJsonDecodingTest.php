<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

it('documents that laravel decodes a json body while capturing the request', function () {
    $symfony = SymfonyRequest::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"a":1,"b":2}');

    $request = Request::createFromBase($symfony);

    expect($request->request->all())->toBe(['a' => 1, 'b' => 2]);
});
