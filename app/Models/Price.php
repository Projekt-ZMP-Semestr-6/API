<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'actual_price',
        'lowest_price',
        'highest_price',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}

