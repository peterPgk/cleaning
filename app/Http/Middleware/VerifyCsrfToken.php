<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'company/signup/step2/logo',
        'company/signup/step3/liability-image',
        'admin/company/general/logo',
	    'admin/company/additional/liability'
    ];
}
