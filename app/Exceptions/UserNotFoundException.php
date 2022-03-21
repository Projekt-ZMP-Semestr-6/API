<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserNotFoundException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('User not found.', 500)
        );
    }
}
