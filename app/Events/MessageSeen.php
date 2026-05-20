<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSeen implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;
    public $senderId;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->senderId = $message->sender_id ?? null;
        $this->receiverId = $message->receiver_id ?? null;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->receiverId),
            new PrivateChannel('chat.' . $this->senderId),
        ];
    }


    public function broadcastWith()
    {
        return [
            'seen' => $this->message->seen,
        ];
    }
}
