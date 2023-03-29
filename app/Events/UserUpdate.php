<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $balance;
    public $loss_reward_balance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user_id, $balance, $loss_reward_balance)
    {
        $this->user_id = $user_id;
        $this->balance = $balance;
        $this->loss_reward_balance = $loss_reward_balance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('User.Update.' . $this->user_id);
    }
}
