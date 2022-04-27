<?php

declare(strict_types = 1);

namespace App\Exceptions\Game;

use Exception;

class GamesNotFetchedException extends Exception
{
    public function __construct()
    {
        parent::__construct('The problem occurred while tried to fetch games', 500);
    }
}
