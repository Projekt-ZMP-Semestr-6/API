<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->keyType = 'string';
            $model->incrementing = false;

            $keyName = $model->getKeyName();
            $model->$keyName = $model->$keyName ?? Str::orderedUuid();
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
