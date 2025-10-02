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

class MessageBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private $userFromID;
    private $userToID;
    private $message;

    public function __construct($userFromID, $userToID, $message)
    {
        //
        $this->userFromID = $userFromID;
        $this->userToID = $userToID;
        $this->message = $message;
    }

    public function broadcastAs()
    {
        return 'messageEvent';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('message.' . $this->userToID),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'userFromID' => $this->userFromID,
            'userToID' => $this->userToID,
            'message' => $this->message,
        ];
    }
}
