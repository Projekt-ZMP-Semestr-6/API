<?php

declare(strict_types = 1);

namespace App\Exceptions\Auth;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LogoutException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse('User not log out.', 500)
        );
    }
}
