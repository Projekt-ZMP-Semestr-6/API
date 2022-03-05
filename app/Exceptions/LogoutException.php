<?php

declare(strict_types = 1);

namespace App\Exceptions;

use Exception;

class LogoutException extends Exception
{
    public function __construct() {
        parent::__construct('User not log out.', 500);
    }
}
