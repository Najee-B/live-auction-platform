<?php

namespace App\Events;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $bid;
    public $product;

    public function __construct(Bid $bid, Product $product)
    {
        $this->bid = $bid;
        $this->product = $product;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('product.' . $this->product->id),
        ];
    }

    public function broadcastAs()
    {
        return 'BidPlaced'; 
    }

    public function broadcastWith(): array
    {
        return [
            'bid' => [
                'amount' => $this->bid->amount,
                'user_id' => $this->bid->user_id,
            ],
            'product' => [
                'id' => $this->product->id,
                'end_time' => $this->product->end_time->toIso8601String(),
            ],
        ];
    }
}