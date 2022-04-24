<?php

declare(strict_types = 1);

namespace App\Exceptions\Game;

use Exception;

class DetachingGameException extends Exception
{
    public function __construct()
    {
        parent::__construct('The problem occured while trying to detach the game', 500);
    }
}
