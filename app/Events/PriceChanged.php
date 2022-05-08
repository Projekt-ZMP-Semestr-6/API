<?php

declare(strict_types = 1);

namespace App\Events;

use App\Http\Resources\PriceNotificationResource;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PriceChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PriceNotificationResource $content;

    public function __construct(Game $game)
    {
        $this->content = new PriceNotificationResource($game);
    }

    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('price.notify.' . $this->content->appid);
    }

    public function broadcastAs(): string
    {
        return 'price.changed';
    }
}
