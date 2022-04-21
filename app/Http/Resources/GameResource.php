<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'appid' => $this->appid,
            'last_modified' => $this->last_modified,
            'header_img' => $this->getHeaderImage(),
        ];
    }
}
