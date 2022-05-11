<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        $lastPrice = $this->lastPrice;
        $actualPrice = $this->actualPrice;
        $lowestPrice = $this->lowestPrice;
        $highestPrice = $this->highestPrice;

        return [
            'name' => $this->name,
            'appid' => $this->appid,
            'last_price' => ($lastPrice['price'] / 100),
            'last_price_date' => $lastPrice['date'],
            'new_price' => ($actualPrice->price / 100),
            'lowest_price' => ($lowestPrice->price / 100),
            'lowest_price_date' => $lowestPrice->updated_at,
            'highest_price' => ($highestPrice->price / 100),
            'highest_price_date' => $highestPrice->updated_at,
        ];
    }
}
