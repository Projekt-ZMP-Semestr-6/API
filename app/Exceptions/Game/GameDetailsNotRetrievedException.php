<?php

declare(strict_types = 1);

namespace App\Exceptions\Game;

use Exception;

class GameDetailsNotRetrievedException extends Exception
{
    public function __construct()
    {
        parent::__construct('The external API responded with "success: false"', 500);
    }
}
