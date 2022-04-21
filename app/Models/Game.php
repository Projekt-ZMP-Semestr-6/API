<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="Game",
 * type="object",
 * @OA\Property(property="id", type="string", example="487"),
 * @OA\Property(property="name", type="string", example="LEGO® Batman™: The Videogame"),
 * @OA\Property(property="appid", type="string", example="21000"),
 * @OA\Property(property="last_modified", type="date:unix_timestamp", example="1573509038"),
 * )
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'appid',
        'last_modified',
        'price_change_number',
    ];
}
