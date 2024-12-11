<?php

namespace App\Events;

use App\Packages\RequestInput\SaveProductInput;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private SaveProductInput $input;
    /**
     * Create a new event instance.
     */
    public function __construct(SaveProductInput $input)
    {
        $this->input = $input;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('product.create.fanout'),
        ];
    }
}
