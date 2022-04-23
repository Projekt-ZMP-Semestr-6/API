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
 * @OA\Property(property="header_img", type="string", example="https://cdn.cloudflare.steamstatic.com/steam/apps/21000/capsule_184x69.jpg?t=1573509038"),
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

    public function getHeaderImage(): string
    {
        return env('MEDIA_SRC') . $this->appid . "/capsule_184x69.jpg" . "?t={$this->last_modified}";
    }
}
