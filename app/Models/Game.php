<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="Game",
 * type="object",
 * @OA\Property(property="id", type="string", example="612"),
 * @OA\Property(property="name", type="string", example="14.95"),
 * @OA\Property(property="appid", type="string", example="21000"),
 * @OA\Property(property="last_modified", type="date:unix_timestamp", example="1602536047"),
 * @OA\Property(property="price_change_number", type="int", example="13853601"),
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
