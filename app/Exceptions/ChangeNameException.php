<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ChangeNameException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('Name not updated', 500)
        );
    }
}
