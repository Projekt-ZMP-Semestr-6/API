<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HighestPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
