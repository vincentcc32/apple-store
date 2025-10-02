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

class OrderStatusBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $status;
    public $message;
    public $orderStatus;
    public $userId;
    public $targget;
    /**
     * Create a new event instance.
     */
    public function __construct($orderId, $status, $message, $orderStatus, $userId, $targget)
    {
        //
        $this->orderId = $orderId;
        $this->status = $status;
        $this->message = $message;
        $this->orderStatus = $orderStatus;
        $this->userId = $userId;
        $this->targget = $targget;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->targget === 'admin') {
            return [new PrivateChannel('admin.order-status')];
        }
        Log::info('broad', ['userId' => $this->userId]);
        return [new PrivateChannel('order-status.' . $this->userId)];
    }

    public function broadcastAs()
    {
        return 'OrderStatusEvent';
    }


    public function broadcastWith(): array
    {
        return [
            'orderId'   => $this->orderId,
            'status'    => $this->status,
            'message'   => $this->message,
            'orderStatus' => $this->orderStatus,
            'userID' => $this->userId,
        ];
    }
}
