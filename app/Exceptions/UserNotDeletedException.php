<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserNotDeletedException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('User not deleted!', 500)
        );
    }
}
