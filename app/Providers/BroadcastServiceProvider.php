<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use App\Events\BidPlaced;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes();

        require base_path('routes/channels.php');

        ;

        Event::listen(BidPlaced::class, function ($event) {
            \Log::info('📢 Broadcasting BidPlaced event data:', $event->broadcastWith());
        });
    }
}