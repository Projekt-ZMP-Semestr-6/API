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
        $actualPrice = $this->actualPrice;
        $lowestPrice = $this->lowestPrice;
        $highestPrice = $this->highestPrice;

        return [
            'appid' => $this->appid,
            'new_price' => $actualPrice->price,
            'lowest_price' => $lowestPrice->price,
            'lowest_price_date' => $lowestPrice->updated_at,
            'highest_price' => $highestPrice->price,
            'highest_price_date' => $highestPrice->updated_at,
        ];
    }
}
