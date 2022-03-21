<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ChangePasswordException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('Password not updated', 500)
        );
    }
}
