<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserOrderBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private $orderId;
    private $status;
    public function __construct($orderId, $status)

    {
        //
        $this->orderId = $orderId;
        $this->status = $status;
    }

    public function broadcastAs()
    {
        return 'UserOrderEvent';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user-order'),
        ];
    }

    public function broadcastWith(): array
    {
        Log::info('Broadcasting UserOrderBroadcast', [
            'orderId' => $this->orderId,
            'status' => $this->status
        ]);
        return [
            'orderId' => $this->orderId,
            'status' => $this->status,
        ];
    }
}
