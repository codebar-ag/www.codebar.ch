<?php

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\TestCase;

uses(TestCase::class)->in('Core', 'Feature', 'Helper', 'Unit');

function createRequest(string $method, string $uri): Request
{
    return Request::createFromBase(SymfonyRequest::create($uri, $method));
}
