<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat;
    public $product;

    public function __construct(Chat $chat, Product $product)
    {
        $this->chat = $chat;
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
        return 'ChatMessageSent'; 
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => [
                'message' => $this->chat->message,
                'user' => [
                    'name' => $this->chat->user->name,
                ],
            ],
        ];
    }
}