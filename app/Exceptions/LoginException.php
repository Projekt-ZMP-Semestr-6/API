<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('Incorrect credentials', 422)
        );
    }
}
