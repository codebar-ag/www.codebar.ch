<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;

class PreventRequestForgery extends Middleware
{
    /**
     * No JavaScript reads the XSRF-TOKEN cookie (forms use the @csrf field),
     * so the unprefixed cookie is not sent at all.
     *
     * @var bool
     */
    protected $addHttpCookie = false;
}
