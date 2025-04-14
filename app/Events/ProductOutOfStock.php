<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductOutOfStock implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $outOfStockItems;

    public function __construct($outOfStockItems)
    {
        $this->outOfStockItems = $outOfStockItems;
    }

    public function broadcastOn()
    {
        return new Channel('inventory');
    }

    public function broadcastAs()
    {
        return 'product.out-of-stock';
    }
} 