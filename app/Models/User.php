<?php

declare(strict_types = 1);

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 * schema="User",
 * type="object",
 * @OA\Property(property="id", type="uuid", example="95c0fbe0-2ae6-4a99-a41c-14b0a68cf057"),
 * @OA\Property(property="name", type="string", example="Greg"),
 * @OA\Property(property="email", type="email", example="test@test.com"),
 * )
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => Registered::class,
    ];

    public function observedGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'game_user');
    }

    public function delete(): bool|null
    {
        $this->tokens()->delete();

        return parent::delete();
    }
}
